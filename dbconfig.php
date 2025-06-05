/**
 * Database Configuration File
 * This file contains the database connection settings and establishes the connection
 * to the MySQL database using mysqli.
 */

// Database connection constants
define("HOSTNAME","localhost");
define("USERNAME","root");
define("PASSWORD","");
define("DATABASE","assignment");

// Establish database connection
$connection = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

// Check if connection was successful
if(!$connection){
    die("connection failed");
}
// else{
//     echo "yess";
// }

?>