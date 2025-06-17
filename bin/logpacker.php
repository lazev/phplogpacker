<?php
console('');
console('°º¤ø,¸¸,ø¤º°`° LOG PACKER º¤ø,¸¸,ø¤º°`°º', 'azul', 'negrito');
console('');

if(!isset($argv[1])) {
	console('Informe o caminho no segundo argumento.', 'amarelo', 'negrito');
	die(PHP_EOL);
}

$path = $argv[1];

if(!file_exists($path)) {
	console($path .' não encontrado.', 'vermelho', 'negrito');
	die(PHP_EOL);
}

$num_files_archived = 5; //Max 50
$max_file_size_MB   = 50;
$days_last_change   = 0;
$days_from_creation = 0;
$archive_extension  = '7z'; //7z | zip

if(file_exists($path .'/logpacker.ini')) {

	$arr = parse_ini_file($path .'/logpacker.ini');

	if(isset($arr['num_files_archived'])) $num_files_archived = $arr['num_files_archived']; //Max 50
	if(isset($arr['max_file_size_MB']))   $max_file_size_MB   = $arr['max_file_size_MB'];
	if(isset($arr['days_last_change']))   $days_last_change   = $arr['days_last_change'];
	if(isset($arr['days_from_creation'])) $days_from_creation = $arr['days_from_creation'];
	if(isset($arr['archive_extension']))  $archive_extension  = $arr['archive_extension'];
}

console('CONFIG', 'verde');
console('num_files_archived: '. $num_files_archived);
console('max_file_size_MB:   '. $max_file_size_MB  );
console('days_last_change:   '. $days_last_change  );
console('days_from_creation: '. $days_from_creation);
console('archive_extension:  '. $archive_extension );
console('');

$extfile = ($archive_extension == '7z' && shell_exec('command -v 7za')) ? '.7z': '.zip';

rotate($path);


function rotate($path) {

	if(!is_dir($path)) {

		rotateFile($path);

	} else {

		$path = rtrim($path, '/');

		foreach(scandir($path) as $item) {
			if($item == '.' || $item == '..' || $item == 'logpacker.ini') continue;

			if(substr($item, -4) == '.zip' || substr($item, -3) == '.7z') continue;

			$file = $path .'/'. $item;

			if(is_dir($file)) {
				console($file .'/', 'amarelo');
				rotate($file);
			}
			else {
				rotateFile($file);
			}
		}
	}
}


function rotateFile($file) {
	global $extfile;

	$ii = 1;

	if(!rotateValid($file)) {
		console('  '. basename($file));
		return false;
	}

	rotateOld($file);

	do {
		$newfile = $file .'.'. $ii;
		$ii++;
	} while(file_exists($newfile));

	console('  '. basename($file) .' => '. basename($newfile) . $extfile, 'azul', 'negrito');

	if($extfile == '.7z') {
		shell_exec("mv $file $newfile && 7za a $newfile.7z $newfile -mx9 -sdel");
	} else {
		shell_exec("mv $file $newfile && zip -mj9 $newfile.zip $newfile");
	}

	return true;
}


function rotateOld($file) {
	global $num_files_archived, $extfile;

	$tmp = pathinfo($file);

	$path = $tmp['dirname'];
	$name = $tmp['basename'];

	for($ii=50; $ii>=1; $ii--) {
		if(file_exists($path .'/'. $name .'.'. $ii . $extfile)) {

			if($ii == $num_files_archived) {
				console('  '. $name .'.'. $ii . $extfile .' => delete', 'ciano');
				unlink($path .'/'. $name .'.'. $ii . $extfile);
			} else {
				console('  '. $name .'.'. $ii . $extfile .' => '. $name .'.'. ($ii+1) . $extfile, 'ciano', 'negrito');
				rename(
					$path .'/'. $name .'.'. $ii . $extfile,
					$path .'/'. $name .'.'. ($ii+1) . $extfile
				);
			}
		}
	}
}


function rotateValid($file) {
	global $max_file_size_MB, $days_last_change, $days_from_creation;

	if($max_file_size_MB) {
		if(filesize($file) > $max_file_size_MB*1024*1024) return true;
	}
	//TODO
	//$days_last_change
	//$days_from_creation
}


function console($msg, $cor='branco', $estilo='normal', $fundo='preto', $enteres=1) {

	$tabCor = [
		'preto'    => 0,
		'vermelho' => 1,
		'verde'    => 2,
		'amarelo'  => 3,
		'azul'     => 4,
		'rosa'     => 5,
		'ciano'    => 6,
		'branco'   => 7
	];

	$tabEstilo = [
		'normal'     => 0,
		'negrito'    => 1,
		'opaco'      => 2,
		'italico'    => 3,
		'sublinhado' => 4,
		'piscando'   => 5,
		'reverso'    => 7,
		'oculto'     => 8,
		'riscado'    => 9
	];

	$cor = $tabCor[$cor] ?: 7;
	$estilo = $tabEstilo[$estilo] ?: 0;
	$fundo = $tabCor[$fundo] ?: 0;

	$enter = '';
	for($ii=0; $ii<$enteres; $ii++) $enter .= PHP_EOL;

	echo "\033[0$estilo;3$cor;4${fundo}m$msg\033[00m$enter";
}
