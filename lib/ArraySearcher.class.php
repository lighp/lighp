<?php
namespace lib;

/**
 * A tool to search through an array.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ArraySearcher {
	/**
	 * The data.
	 * @var array
	 */
	protected $data;

	/**
	 * A list of accented characters.
	 * @var array
	 */
	protected static $accentedChars = array(
		'A|À|Á|Â|Ã|Ä|Å',
		'a|à|á|â|ã|ä|å',
		'O|Ò|Ó|Ô|Õ|Ö|Ø',
		'o|ò|ó|ô|õ|ö|ø',
		'E|È|É|Ê|Ë',
		'e|é|è|ê|ë',
		'C|Ç',
		'c|ç',
		'I|Ì|Í|Î|Ï',
		'i|ì|í|î|ï',
		'U|Ù|Ú|Û|Ü',
		'u|ù|ú|û|ü',
		'y|ÿ',
		'N|Ñ',
		'n|ñ'
	);

	/**
	 * Construct an array searcher.
	 * @param array $data The data. Data must be a 2-dimension array.
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * Get the searcher's data.
	 * @return array The data.
	 */
	public function data() {
		return $this->data;
	}

	/**
	 * Search in the searcher's data.
	 * @param  string $query        The search query.
	 * @param  array $searchFields  A list of search fields. If ommited, the query will be searched in all fields.
	 * @return array                Data, sorted by revelance.
	 */
	public function search($query, array $searchFields) {
		//Escape the query string
		$escapedQuery = preg_quote(trim($query));
		$escapedQuery = str_replace(' ', '|', $escapedQuery); //" " = OR
		foreach (self::$accentedChars as $chars) { //Accented chars tolerance
			$regex = '('.$chars.')';
			$escapedQuery = preg_replace('#'.$regex.'#', $regex, $escapedQuery);
		}

		if (empty($escapedQuery)) { //Empty query
			return $this->data;
		}

		$matchingItems = array();

		$nbrFields = count($searchFields);

		$nbrItems = count($this->data);
		$hitsPower = strlen((string) $nbrItems);
		$hitsFactor = pow(10, $hitsPower);

		$i = 0;
		foreach($this->data as $item) {
			$itemHits = 0;

			if ($nbrFields == 0) {
				$nbrFields = count($item);
			}

			$j = 0;
			foreach ($item as $field => $value) {
				if ($nbrFields != 0 && !in_array($field, $searchFields)) {
					continue;
				}

				$item[$field] = preg_replace('#('.$escapedQuery.')#i', '<strong>$1</strong>', (string) $item[$field], -1, $fieldHits);
				$itemHits += $fieldHits * ($nbrFields - $j);

				$j++;
			}

			if ($itemHits > 0) {
				$matchingItems[$itemHits * $hitsFactor + ($nbrItems - $i)] = $item;
			}

			$i++;
		}

		krsort($matchingItems);
		$matchingItems = array_values($matchingItems);

		return $matchingItems;
	}
}