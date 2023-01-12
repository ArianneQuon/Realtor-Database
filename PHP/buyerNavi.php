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
        <title>Buyer Navigation Page</title>
        
    </head>

    <body>

        <a href="homepage.php">  
       <button>Back to Home</button>  
        </a>
        
        <header>
            <h1>Welcome to Buyer Navigation</h1>
        </header>

        <a href="buyerViewPast.php">  
       <button>View Past Properties</button>  
        </a>

        <fieldset>
        <form method="post" action="buyerNavi.php">
            <legend>Please Choose 1 Type of Property</legend>
                <input type="checkbox" id="office" name="office">
                <label for="office">Office Buildings</label><br/>
            
            
                <input type="checkbox" id="house" name="house">
                <label for="house">House</label><br/>

                <input type="checkbox" id="apartment" name="apartment">
                <label for="apartment">Apartment</label><br/>

                <input type ="hidden" id="sqft" name="sqft">
                Filter Square Footage — more than: <input type="text" id="filtersqft" name = "filtersqft"><br>

            <input type="submit" name="fieldSubmit" value="Submit">

        </form>
        </fieldset>


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
                        <th>PropertyId</th>
                        <th>SellerId</th>
                        <th>Address</th>
                        <th>Square Footage</th>
                        <th>Price</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] . "</td><td>". $row[3]. "</td><td>". $row[4]."</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

            function connectToDB() {
                global $db_conn;
                $db_conn = OCILogon("ora_msiem", "a41623620", "dbhost.students.cs.ubc.ca:1522/stu");

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
                    $filtersqft = $_POST['filtersqft'];

                    //ONLY OFFICE
                    if (isset($_POST['office']) && !isset($_POST['house']) && !isset($_POST['apartment'])) {
                        echo"Office Buildings Available";
                        //if (isset($_POST['<500']) && !isset($_POST['500-1000']) && !isset($_POST['>1000'])){
                            //only less than 500
                            $insert_bquery = "select p.propertyid, p.sellerid, p.propertyaddress, p.sqft, p.buyprice, p.buyerid
                                                from property p join officebuilding o on p.propertyid = o.propertyid
                                                where p.sqft > %d AND p.buyerid IS NULL";
                            $formatted_insert_bquery = sprintf($insert_bquery, $filtersqft);
                            $result = executePlainSQL($formatted_insert_bquery);
                        //ONLY HOUSE
                    } else if (!isset($_POST['office']) && isset($_POST['house']) && !isset($_POST['apartment'])) {
                        echo"House available";
                        $insert_bquery = "select p.propertyid, p.sellerid, p.propertyaddress, p.sqft, p.buyprice, p.buyerid
                                                from property p join house h on p.propertyid = h.propertyid
                                                where p.sqft > %d AND p.buyerid IS NULL";
                            $formatted_insert_bquery = sprintf($insert_bquery, $filtersqft);
                            $result = executePlainSQL($formatted_insert_bquery);
                        
                        //only APARTMENT 
                    } else if (!isset($_POST['office']) && !isset($_POST['house']) && isset($_POST['apartment'])) {
                        echo"Apartment Available";
                        $insert_bquery = "select p.propertyid, p.sellerid, p.propertyaddress, p.sqft, p.buyprice, p.buyerid
                                                from property p join apartment a on p.propertyid = a.propertyid
                                                where p.sqft > %d AND p.buyerid IS NULL";
                            $formatted_insert_bquery = sprintf($insert_bquery, $filtersqft);
                            $result = executePlainSQL($formatted_insert_bquery);
                    }
                    
                    printResult($result);
                }
                
            
            
            disconnectFromDB();

    ?> 

    </body>
</html>
