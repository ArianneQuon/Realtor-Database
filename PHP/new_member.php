
<html>
    <head>
        <title>New Member</title>

    </head>

    <body>
        <a href="homepage.php">  
       <button>Back to Home</button>  
        </a>
        
        <header>
            <h1>Register as New Member</h1>
        </header>
        
        <h2>Buyer</h2>
        <form method ="post" action="new_member.php">
            <input type ="hidden" id="insertBuyer" name="insertBuyer">
            Full Name: <input type="text" id="bname" name = "bname"><br>

            Create ID #:<input type="integer" id="buyerid" name = "buyerid""><br> 
            

            Enter Income Level (1, 2, 3, 4, 5):<input type="integer" id="incomelevel" name="incomelevel"><br>

            Enter Email:<input type="text" id="email" name = "email"><br>
            <input type="submit" id = "bSubmit" value="Submit">

        </form>

<!-- TODO --> 
        <h2>Seller</h2> <!-- not sure if selelrs should input their own branchid or nah -->
        <form method ="post" action="new_member.php">
            <input type="hidden" id="insertSeller" name="insertSeller">
            Full Name:<input type="text" id="sname" name = "sname"><br>

            Create ID #:<input type="integer" id="sellerid" name = "sellerid"><br>

            Enter BankID:<input type="integer" id="branchid" name = "branchid"><br>

            <input type="submit" id = "sSubmit" value="Submit">
            

        </form>

        <h2>Realtor</h2>
        <form method="post" action="new_member.php">
            <input type="hidden" id="insertRealtor" name="insertRealtor">
            Full Name:<input type="text" id="rfullname" name = "rfullname"><br>

            Create ID #:<input type="integer" id="realtorid" name = "realtorid" "><br>

            Company Name:<input type="text" id="rcompany" name = "rcompany"><br>
            <input type="submit" id="rSubmit" value="Submit">

        </form>


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



        function handleBuyerInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table

                $buyerid = $_POST['buyerid'];
                $bname = $_POST['bname'];
                $email = $_POST['email'];
                $incomelevel = $_POST['incomelevel'];

                $insert_bquery = "insert into Buyer(buyerID, bname, contactinfo, incomelevel, contractnum) values(%d, '%s', '%s', %d, NULL)";
                $formatted_insert_bquery = sprintf($insert_bquery, $buyerid, $bname, $email, $incomelevel);
                executePlainSQL($formatted_insert_bquery);

                
                echo"<h3 style='text-align:center'> Welcome $bname! Your ID is $buyerID</h3>";
              
                OCICommit($db_conn);
    
                disconnectFromDB();
        }

        // not inserting the branchid properly
        function handleSellerInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
          
            $sellerid = $_POST['sellerid'];
            $sname = $_POST['sname'];
            $branchid = $_POST['branchid'];

            $insert_squery = "insert into Seller(sellerid, branchid, sname, transactionnumber) values(%d, %d, '%s', NULL)";
            $formatted_insert_squery = sprintf($insert_squery, $sellerid, $branchid, $sname);
            executePlainSQL($formatted_insert_squery);

            
            echo"<h3 style='text-align:center'> Welcome $sname! Your ID is $sellerid</h3>";
          
            OCICommit($db_conn);

            disconnectFromDB();
        }

        // not inserting the rcompany properly
        function handleRealtorInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            
            $realtorid = $_POST['realtorid'];
            $rname = $_POST['rfullname'];
            $rcompany = $_POST['rcompany'];

            $insert_rquery = "insert into Realtor(realtorID, rname, rcompany) values(%d, '%s', '%s')";
            $formatted_insert_rquery = sprintf($insert_rquery, $realtorid, $rname, $rcompany);
            executePlainSQL($formatted_insert_rquery);
            
            echo"<h3 style='text-align:center'> Welcome $rname! Your ID is $realtorid</h3>";
          
            OCICommit($db_conn);

            disconnectFromDB();
        }



        if (connectToDB()) {
            if (array_key_exists('insertBuyer', $_POST)) {
                handleBuyerInsertRequest();
            } else if (array_key_exists('insertSeller', $_POST)) {
                handleSellerInsertRequest();
            } else if (array_key_exists('insertRealtor', $_POST)) {
                handleRealtorInsertRequest();
            }
        }

        disconnectFromDB();
     
 

        ?>

    </body>

</html>
                                               
