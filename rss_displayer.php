<?php
require getcwd() . '/vendor/autoload.php'; // Composer

header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
header("Content-Type: text/plain; charset=ISO-8859-1");

$feed = new SimplePie();
$feed->set_feed_url(str_replace("]]", "", str_replace("![CDATA[", "", $_REQUEST["feedurl"])));
$feed->init();
$feed->handle_content_type();

$wc24mimebounary = "BoundaryForDL" . date("YmdHi") . "/" . rand(1000000, 9999999);

echo "--".$wc24mimebounary."\r\n";
echo "Content-Type: text/plain\r\n\r\n";
echo "This part is ignored.\r\n\r\n\r\n";

foreach($feed->get_items() as $item)
{
	if (time() - strtotime($item->get_date(), $now = time()) > 3600)
	{
		continue;
	}
	
	/* Create the main body text. */

	echo "--".$wc24mimebounary."\r\n".
	"Content-Type: text/plain\r\n\r\n".
	"Date: " . gmdate('D, d M Y H:i:s') . " +0000 (UTC)\r\n".
	"From: w9999999900000000@rc24.xyz\r\n".
	"To: allusers@rc24.xyz\r\n".
	"Subject: \r\n".
	"MIME-Version: 1.0\r\n".
	"Content-Type: text/plain; charset=utf-8\r\n".
	"Content-Transfer-Encoding: 7bit\r\n".
	"X-Wii-AltName: " . base64_encode(mb_convert_encoding($_REQUEST["title"], "UTF-16", "auto")) . "\r\n".
	"X-Wii-MB-NoReply: 1\r\n\r\n";

	$raw_description = new \Html2Text\Html2Text($item->get_description());
	$description = $raw_description->getText();

	echo $description . "\r\n\r\n" . "Link: " . $item->get_link() . "\r\n\r\n";

	/* Create the chjump used for opening the link in the (ew) Internet Channel. */

	/*echo "--".$wc24mimebounary."\r\n".
	"Content-Type: Application/X-Wii-MsgBoard; name=internet_jump.arc\r\n\r\n".
	"Content-Transfer-Encoding: base64\r\n".
	"Content-Disposition: attachment; filename=internet_jump.arc\r\n\r\n";

	$h = pack("H*", "43684A7000000034000000010000000000010001484144410000002000000014") . $item->get_link();
	mkdir("./tmp");
	$f = fopen("./tmp/chjump.bin", "wb+");
	fwrite($f, $h);
	$create_u8 = shell_exec("wszst CREATE tmp --u8 --no-compress");
	$u8_output = fopen("tmp.u8", "rb"); # I wish Wiimm set the output extension to be .arc in this case. Oh well..
	$chjump = base64_encode(fread($u8_output, filesize("tmp.u8")));
	echo $chjump . "\r\n\r\n";
	fclose($u8_output);*/

}
echo "--".$wc24mimebounary."--"."\r\n\r\n";
?>
