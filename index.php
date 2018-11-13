<?PHP
include_once("config/setup.php");
include_once("authentication/session.php");
?>
<!DOCTYPE HTML>
<HTML>
	<HEAD>
        <LINK rel="stylesheet" type="text/css" href="style/camagru.css">
		<TITLE>
			Camagru
		</TITLE>
	</HEAD>
	<BODY>
		<H1>
			Camagru
		</H1>
        <A href="index.php">Home</A>
        <A href="upload.php">Upload</A>
        <A href="#">Take/Edit Photo</A>
		<HR>
<?PHP
$sql = "SELECT COUNT(*) FROM images";
$statement = $connection->prepare($sql);
$statement->execute();
$row = $statement->fetch();
$num_rows = $row[0];
$rows_per_page = 2;
$total_pages = ceil($num_rows / $rows_per_page);
if (isset($_GET['current_page']) && is_numeric($_GET['current_page']))
{
    $current_page = (int) $_GET['current_page'];
}
else
{
    $current_page = 1;
}
if ($current_page > $total_pages)
{
    $current_page = $total_pages;
}
if ($current_page < 1)
{
    $current_page = 1;
}
$offset = ($current_page - 1) * $rows_per_page;
$sql = "SELECT * FROM images LIMIT $offset, $rows_per_page";
$statement = $connection->prepare($sql);
$statement->execute();
while ($row = $statement->fetch())
{
    echo "<IMG src='images/".$row['image']."' height='300' width='200'>'";
}
if (!isset($_SESSION['username'])):
?>
		<P>
			You are currently not signed in 
			<a href="authentication/login.php">Login</a> 
			Not yet a member?
			<a href="authentication/signup.php">Signup</a>
		</P>
<?PHP else: ?>
		<P>
		You are logged in as <?PHP if(isset($_SESSION['username'])) echo $_SESSION['username'];?>
			<A href="authentication/logout.php">Logout</A>
</P>
<?PHP
endif;
/**Pagination Links **/
$range = 3;
if ($current_page > 1)
{
    $prev_page = $current_page - 1;
    echo "<A href='{$_SERVER['PHP_SELF']}?current_page=$prev_page'>Previous</A>";
    echo " ";
}
for ($i = ($current_page - $range); $i < ($current_page + $range + 1); $i++)
{
    if (($i > 0) && ($i <= $total_pages))
    {
        if ($i == $current_page)
        {
            echo "<b>$i</b>";
        }
        else
            {
                echo "<A href='{$_SERVER['PHP_SELF']}?current_page=$i'>$i</A>";
                echo " ";
            }
    }
}
if ($current_page != $total_pages)
{
    $next_page = $current_page + 1;
    echo "<A href='{$_SERVER['PHP_SELF']}?current_page=$next_page'>Next</A>";
}

?>
	</BODY>
</HTML>
