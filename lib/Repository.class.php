<?php
namespace lib;

interface Repository {
	public function getPackagesList();
	public function getPackage($pkgName);
	public function packageExists($pkgName);
}