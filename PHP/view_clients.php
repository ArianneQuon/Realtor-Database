
<html>
    <head>
        <style>
            table {
              border-collapse: collapse;
              width: 100%;
            }
            
            td, th {
              border: 1px solid #dddddd;
              text-align: left;
              padding: 8px;
            }
            
        </style>
        <title>View Clients</title>
    </head>

    <body>
        <a href="realtor_home.php">
            <button type="button">Back</button>  
        </a>
        
        <header>
            <h1>View Clients</h1>
        </header>


        <fieldset>
            <legend>Type of Client (Choose one only):</legend>
            
            <form method="post" action="view_clients.php">
                <input type="checkbox" id="buyer" name="buyer" value="buyer">
                <label for="buyer">Buyers</label><br/>
            
	<a href="join_client.php">
            <button type="button">More Buyer Option</button>    
        </a> <br>
            
                <input type="checkbox" id="seller" name="seller" value = "seller">
                <label for="seller">Seller</label><br/>
        </fieldset>

        <fieldset>
        </fieldset?

        <fieldset>
        <legend>View:</legend>
        <div>
        <form method="post" action="view_clients.php">
            <input type="checkbox" id="name" name="name">               
            <label for="id">Name</label>
            </div>
            <div>
                <input type="checkbox" id="propertyid" name="propertyid" >
                <label for="name">PropertyID</label>
            </div>
            <div>
                <input type="checkbox" id="address" name="address">
                <label for="address">Address</label>
                </fieldset>
                <input type="submit" name="fieldSubmit" value="Submit">
            </form>
        </fieldset>
        </div>
        

        <?php
  
        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            
            echo "<table>";
            echo "<tr>
            </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] ."</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        } 
        

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_aquon01", "a11512589", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        connectToDB();

        if ($db_conn){
            
           if (isset($_POST['buyer']) && isset($_POST['propertyid']) && isset($_POST['name']) && isset ($_POST['address'])){
                // all 3 selected
                $result = executePlainSQL("select b.buyerID, bname, propertyid, propertyaddress from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, name, propertyid, property addresses</h3> of buyers";     

            } else if (isset($_POST['buyer']) && isset($_POST['propertyid']) && !isset($_POST['name']) && isset ($_POST['address'])) {
                // propertyid and address
                $result = executePlainSQL("select b.buyerID, p.propertyid from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, propertyid</h3> of buyers";

            } else if (isset($_POST['buyer']) && !isset($_POST['propertyid']) && isset($_POST['name']) && isset ($_POST['address'])) {
                // name and address
                $result = executePlainSQL("select b.buyerid, bname, propertyaddress from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, name, bought property addresses</h3> of buyers";

            } else if (isset($_POST['buyer']) && isset($_POST['propertyid']) && isset($_POST['name']) && !isset($_POST['address'])) {
                // name and propertyid
                $result = executePlainSQL("select b.buyerid, bname, propertyid from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, name, propertyid</h3> of buyers";

            } else if (isset($_POST['buyer']) && isset($_POST['propertyid']) && !isset($_POST['name']) && !isset($_POST['address'])) {   
                // only address             
                $result = executePlainSQL("select b.buyerid, propertyid from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, propertyid</h3> of buyers";

            } else if (isset($_POST['buyer']) && !isset($_POST['propertyid']) && isset($_POST['name']) && !isset($_POST['address'])) {   
                // only name             
                $result = executePlainSQL("select b.buyerid, bname from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id, names</h3> of buyers";
            } else if (isset($_POST['buyer']) && !isset($_POST['propertyid']) && !isset($_POST['name']) && isset($_POST['address'])) {   
                // only address             
                $result = executePlainSQL("select b.buyerid, propertyaddress from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>bought property addresses</h3> of buyers";
            } else if (isset($_POST['buyer'])) {
                // only buyerid
                $result = executePlainSQL("select b.buyerID from Buyer b
                join property p on b.buyerID = p.buyerID order by b.buyerID asc");
                echo"Currently viewing <h3>id</h3> of buyer";


            // sellers 
            } else if (isset($_POST['seller']) && isset($_POST['propertyid']) && isset($_POST['name']) && isset ($_POST['address'])) {
                // all 3 selected
                $result = executePlainSQL("select s.sellerID, sname, propertyid, propertyaddress from Seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, name, propertyid, property addresses</h3> of seller";     

            } else if (isset($_POST['seller']) && isset($_POST['propertyid']) && !isset($_POST['name']) && isset ($_POST['address'])) {
                // propertyid and address
                $result = executePlainSQL("select s.seller, p.propertyid from Seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, propertyid</h3> of seller";

            } else if (isset($_POST['seller']) && !isset($_POST['propertyid']) && isset($_POST['name']) && isset ($_POST['address'])) {
                // name and address
                $result = executePlainSQL("select s.sellerid, sname, propertyaddress from Seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, name, bought property addresses</h3> of seller";

            } else if (isset($_POST['seller']) && isset($_POST['propertyid']) && isset($_POST['name']) && !isset($_POST['address'])) {
                // name and propertyid
                $result = executePlainSQL("select s.sellerid, sname, propertyid from Seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, name, propertyid</h3> of seller";

            } else if (isset($_POST['seller']) && isset($_POST['propertyid']) && !isset($_POST['name']) && !isset($_POST['address'])) {   
                // only address             
                $result = executePlainSQL("select s.sellerid, propertyid from seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, propertyid</h3> of seller";

            } else if (isset($_POST['seller']) && !isset($_POST['propertyid']) && isset($_POST['name']) && !isset($_POST['address'])) {   
                // only name             
                $result = executePlainSQL("select s.sellerid, sname from seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id, names</h3> of seller";

            } else if (isset($_POST['seller']) && !isset($_POST['propertyid']) && !isset($_POST['name']) && isset($_POST['address'])) {   
                // only address             
                $result = executePlainSQL("select s.sellerid, propertyaddress from seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>bought property addresses</h3> of seller";

            } else if (isset($_POST['seller'])) {
                // only buyerid
                $result = executePlainSQL("select s.sellerID from seller s
                join property p on s.sellerID = p.sellerID order by s.sellerID asc");
                echo"Currently viewing <h3>id</h3> of seller";
            }
            
                printResult($result);
            }
        
    
    
    disconnectFromDB();

        ?>

    </body>
</html>
