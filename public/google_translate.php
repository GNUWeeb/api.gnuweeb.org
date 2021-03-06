<?php

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license GNU GPL v2
 *
 * Copyright (C) 2021  Ammar Faizi
 */

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$arg = $_POST;
} else {
	$arg = $_GET;
}


if (!isset($arg["fr"]) || !is_string($arg["fr"])) {
	$code = 400;
	$msg = ["error" => "Missing `fr` string argument"];
	goto out;
}

if (!isset($arg["to"]) || !is_string($arg["to"])) {
	$code = 400;
	$msg = ["error" => "Missing `to` string argument"];
	goto out;
}

if (!isset($arg["text"]) || !is_string($arg["text"])) {
	$code = 400;
	$msg = ["error" => "Missing `text` string argument"];
	goto out;
}


require __DIR__."/../src/GoogleTranslate/src/GoogleTranslate.php";

try {

	$st = new \GoogleTranslate\GoogleTranslate($arg["text"], $arg["fr"],
						   $arg["to"]);
	$code = 200;
	$msg = [
		"error" => NULL,
		"result" => $st->exec()
	];
} catch (\GoogleTranslate\GoogleTranslateException $e) {
	$code = 400;
	$msg = ["error" => $e->getMessage()];
}

out:
header("Content-Type: application/json");
http_response_code($code);
echo json_encode($msg, JSON_UNESCAPED_SLASHES);
exit;
