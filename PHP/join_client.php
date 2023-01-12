<!-- joins buyer and budgetamount tables to get buyer budgets -->

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
        <title>More Buyer Options</title>
    </head>

    <body>
        <a href="view_clients.php">
            <button type="button">Back</button>  
        </a>
    
        <header>
            <h1>More Buyer Options</h1>

        </header>

        <fieldset>
            <!-- users can choose the buyers to show based on their budget -->
        <legend> Find Buyers with Budgets (choose one):</legend>
        <form method="post" action="join_client.php">
                <input type="checkbox" id="all" name="all" value="all">
                <label for="buyer">All</label><br/>
            
                <input type="checkbox" id="two" name="two" value="two">
                <label for="one">Greater than $250000</label><br/>
        
                <input type="checkbox" id="three" name="three" value = "three">
                <label for="seller">Greater than $500000</label><br/>
                <input type="submit" name="submit" value="Submit">
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
                    echo "<tr><th>BuyerID</th><th>Income Level</th><th>Budget</th></tr>";
        
                    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
                    }
        
                    echo "</table>";
                } 
        
                function connectToDB() {
                    global $db_conn;
        
                    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
                    // ora_platypus is the username and a12345678 is the password.
                    $db_conn = OCILogon("ora_hchliu", "a18633396", "dbhost.students.cs.ubc.ca:1522/stu");
        
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
            if ($db_conn) {
                if (isset($_POST[all])) {
                    echo"Viewing buyers of all budget levels";
                    $result = executePlainSQL("select b.buyerid, b.incomelevel, ba.budget 
                               from buyer b join budgetamount ba 
                               on ba.incomelevel=b.incomelevel");
                    printResult($result); 

                } if (isset($_POST[two])) {
                    echo"Viewing buyers with budget >= $250000";
                    $result = executePlainSQL("select b.buyerid, b.incomelevel, ba.budget 
                               from buyer b join budgetamount ba 
                               on ba.incomelevel=b.incomelevel
                               where budget>=250000");
                    printResult($result); 
                } if (isset($_POST[three])) {
                    echo"Viewing buyers with budget >= $500000";
                    $result = executePlainSQL("select b.buyerid, b.incomelevel, ba.budget 
                               from buyer b join budgetamount ba 
                               on ba.incomelevel=b.incomelevel
                               where budget>=500000");
                    printResult($result); 
            }
        }
        
        
      
        ?>
    </body>

</html>
