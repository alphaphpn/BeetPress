<?php

	$md5_given = "#Pviolb/5261^";
	$pw_md5 = md5($md5_given);

	echo "Password: ".$md5_given;
	echo "<br>";
	echo "MD5: ".$pw_md5;
	echo "<br>";
	echo "--------------------------------";
	echo "<br>";

	$password = "user";
	$password2 = "Admin";
	$password3 = "admin";
	$password4 = "W*RY^5!a!@n^";
	$wp_prefix = 'zamboangasibugay';
	$wp_pword = '!ZamboangaSibugay@2022';
	$devusayrshire = 'ShireUsayr@2019#';

	$dtreditor = 'Edy@23#';

	$wp_pw = md5($password4);
	echo "Wordpress Password: ".$wp_pw." | ".$password4;
	echo "<br>";

	echo "Password: ".$password;
	echo "<br>";
	echo "Password2: ".$password2;
	echo "<br>";
	echo "Password2: ".$password3;
	echo "<br><br>";
	echo "devusayrshire: ".$devusayrshire;
	echo "<br><br>";
	echo "DTR Editor: ".$dtreditor;
	echo "<br><br>";

	$encrytp_password = md5($password);
	$encrytp_password2 = md5($password2);
	$encrytp_password3 = md5($password3);
	$encrytp_password4 = md5($devusayrshire);
	$encrytp_password5 = md5($dtreditor);

	echo "Password Encrypt: ".$encrytp_password;
	echo "<br>";
	echo "Password Encrypt2: ".$encrytp_password2;
	echo "<br>";
	echo "Password Encrypt3: ".$encrytp_password3;
	echo "<br><br>";
	echo "Password devusayrshire: ".$encrytp_password4;
	echo "<br><br>";
	echo "Password DTR Editor: ".$encrytp_password5;
	echo "<br><br>";

	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	echo substr(str_shuffle($permitted_chars), 0, 6);
	echo "<br><br>";

	$permitted_chars2 = '0123456789';
	echo substr(str_shuffle($permitted_chars2), 0, 6);
	echo "<br><br>";

	$permitted_chars3 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	echo substr(str_shuffle($permitted_chars3), 0, 6);
	echo "<br><br>";

	$permitted_chars = '~!@^*0123456789~!@^*abcdefghijklmnopqrstuvwxyz~!@^*ABCDEFGHIJKLMNOPQRSTUVWXYZ~!@^*';
	echo "Strong Password<br>";
	echo substr(str_shuffle($permitted_chars), 0, 12);
	echo "<br><br>";

	$permitted_chars3 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@$^*';
	echo "WiFi Password<br>";
	echo substr(str_shuffle($permitted_chars3), 0, 8);
	echo "<br><br>";

	$encryp_wp_prefix = 'abcdefghijklmnopqrstuvwxyz0123456789';
	echo "WP Prefix<br>";
	echo substr(str_shuffle($encryp_wp_prefix), 0, 8).'_'.substr(str_shuffle($encryp_wp_prefix), 0, 6);
	echo "<br><br>";

	$encryp_wp_username = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	echo "WP Database Username<br>";
	echo substr(str_shuffle($encryp_wp_username), 0, 12);
	echo "<br><br>";

	$encryp_wp_password = '~!^*#</>abcdefghijklmnopqrstuvwxyz~!^*#</>ABCDEFGHIJKLMNOPQRSTUVWXYZ~!^*#</>0123456789~!^*#</>';
	echo "WP Database Password<br>";
	echo substr(str_shuffle($encryp_wp_password), 0, 24);
	echo "<br><br>";

	$encryp_symbol = substr(str_shuffle('~!@^*#</>'), 0, 1);
	$encryp_capital = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
	$encryp_small = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);
	$encryp_symbol2 = substr(str_shuffle('~!@^*#</>'), 0, 1);
	$encryp_numbr = substr(str_shuffle('0123456789'), 0, 4);
	$encryp_symbol3 = substr(str_shuffle('~!@^*#</>'), 0, 1);

	echo "<br><br>";

	$encryptforpw = $encryp_symbol.$encryp_capital.$encryp_small.$encryp_symbol2.$encryp_numbr.$encryp_symbol3;
	$md5_pw = md5($encryptforpw);

	echo "Temporary Password: ".$encryptforpw."<br>";
	echo "MD5: ".$md5_pw;
	echo "<br><br>";