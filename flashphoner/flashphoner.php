<?php
/* $Id$ */
/*
	flashphoner.php
	Copyright (C) 2008, 2009 Ken Rice
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/
include "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";
if (permission_exists('flashphoner_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "resources/header.php";

unset ($prep_statement);

if (count($_SESSION['user']['extension']) > 0) {
	$key = uuid();
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$sql = sprintf("INSERT INTO v_flashphone_auth (auth_key, hostaddr, createtime, username) values ('%s', '%s', now(), '%s')",
			$key, $client_ip, $_SESSION["username"]);
	$db->exec(check_sql($sql));
}

// Abort here if we dont have an extension for them and tell them to get one assigned
if (count($_SESSION['user']['extension']) < 1) {
	echo "This user does not have an extension assigned, please Contact your system adminstrator if you feel this is in error<br />\n";
} else if (count($_SESSION['user']['extension']) == 1) {
	// DISPLAY THE PHONE HERE
	$extension = $_SESSION['user']['extension'][0]['user'];
	$extension_uuid = $_SESSION['user']['extension'][0]['extension_uuid'];
	include "phone_html.php";
} else {
	include "phone_choices_html.php";
}

//show the footer
require_once "resources/footer.php";
?>