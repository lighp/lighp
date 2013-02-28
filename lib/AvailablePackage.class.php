<?php
namespace lib;

abstract class AvailablePackage extends Package {
	abstract public function source();

	/**
	 * @deprecated
	 */
	public function install() {
		$pkgFiles = $this->files(); //Get the package's files

		$tmpDir = new \core\TemporaryDirectory(); //Create a temporary folder
		$zipPath = $tmpDir.'/package.zip';

		//First, download the package's source
		$source = fopen($this->source(), 'r'); //Source file
		$dest = fopen($zipPath, 'w'); //Destination file

		$copiedBits = stream_copy_to_stream($source, $dest); //Now we can download the source's file...

		fclose($source);
		fclose($dest);

		if ($copiedBits == 0) { //Check if bytes have been copied
			throw new \RuntimeException('Cannot copy package\'s contents from repository "'.$this->source().'" to temporary folder "'.$tmpDir.'"');
		}

		//Then, extract files
		$zip = new ZipArchive();
		$zip->open($zipPath); //Open the package's source

		$root = __DIR__ . '/../'; //Root folder

		$filesToCopy = array();

		//Check if already goes the right way, and store files to copy in an array
		for ($i = 0; $i < $zip->numFiles; $i++) { //For each file
			//Get info about it
			$itemStat = $zip->statIndex($i); //From the archive
			$itemPkgData = $pkgFiles[$itemStat['name']]; //From the package

			if ($itemPkgData['noextract']) { continue; } //Skip this item

			$itemDestPath = $root . '/' . $itemStat['name']; //Here is the final file's destination

			if (file_exists($itemDestPath)) { //File already exists
				throw new \RuntimeException('File collision detected : "'.$itemDestPath.'" already exists');
			}

			//Add this file in the list
			$filesToCopy[] = array(
				'name' => $itemStat['name'],
				'destPath' => $itemDestPath,
				'md5sum' => $itemPkgData['md5sum']
			);
		}

		//Now, extract files
		foreach($filesToCopy as $item) {
			$itemSource = $zip->getStream($item['name']); //Get the file's stream

			$itemDest = fopen($item['destPath'], 'w'); //Destination file
			$copiedBits = stream_copy_to_stream($itemSource, $itemDest); //Extract current file...
			fclose($itemDest);

			if ($copiedBits == 0) { //Nothing copied -> error ?
				throw new \RuntimeException('Cannot extract file "'.$item['name'].'" from "'.$zipPath.'" to "'.$item['destPath'].'"');
			}

			if (!empty($item['md5sum'])) { //If a md5 checksum is specified
				$destMd5 = md5_file($item['destPath']); //Calculate the MD5 sum of the extracted file

				if ($item['md5sum'] != $destMd5) { //If checksums are different
					unlink($item['destPath']); //Delete copied file
					throw new \RuntimeException('Bad file checksum : "'.$item['destPath'].'". This file is corrupted.');
				}
			}

			chmod($item['destPath'], 0777); //Allow rwx for all users - better for maintaining the framework
		}

		$zip->close(); //Close the package's source
	}
}