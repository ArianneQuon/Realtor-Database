
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
        <title>Hire Maintenance Page</title>
        
    </head>

    <body>
        <a href="homepage.php">
            <button type="button">Back</button>  
        </a>
        
        <header>
            <h1>Welcome to Hire Maintenance</h1>
        </header>

        <fieldset>
        <form method="post" action="hire_maintenance.php">
            <legend>Choose One or Three:</legend>
            <div>
                <input type="checkbox" id="plumber" name="plumber">
                <label for="plumber">Plumber</label>
            </div>
            <div>
                <input type="checkbox" id="cleaner" name="cleaner">
                <label for="cleaner">Cleaner</label>
            </div>
            <div>
                <input type="checkbox" id="gardener" name="gardener">
                <label for="gardener">Gardener</label>
            </div>
        
         
            </fieldset>

            <fieldset>
                <legend> OR choose to see workers that have worked with every seller: </legend>
                <div>
                <input type="checkbox" id="allseller" name="allseller">
                <label for="allseller">Worked with All Sellers</label>
                </div>
        </fieldset>

            <input type="submit" name="fieldSubmit" value="Submit">
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

        function printResult($result) { //prints results from a select statement
            echo "<table>";
                    echo "<tr>
                        <th>Employee Number</th>
                        <th>Company</th>
                        <th>Name</th>
                    </tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2]."</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

            function connectToDB() {
                global $db_conn;
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
                    if (isset($_POST['plumber']) && isset($_POST['cleaner']) && isset($_POST['gardener'])) {
                        echo "All employees";
                        $result = executePlainSQL("select * from Maintenance m"); 
                    //ONLY plumber
                    } else if (isset($_POST['plumber']) && !isset($_POST['cleaner']) && !isset($_POST['gardener'])) {
                        echo" All plumbers";
                        $result = executePlainSQL("select m.empno, m.company, m.mname
                                                    from Maintenance m 
                                                    join plumber p on p.empno = m.empno 
                                                    ");
                    //ALL CLEANERS 
                    } else if (!isset($_POST['plumber']) && isset($_POST['cleaner']) && !isset($_POST['gardener'])) {
                        echo" All cleaners";
                            $result = executePlainSQL("select m.empno, m.company, m.mname
                                                        from Maintenance m 
                                                        join cleaner c on c.empno = m.empno 
                                                        ");
                    //ALL GARDENERS
                    } else if (!isset($_POST['plumber']) && !isset($_POST['cleaner']) && isset($_POST['gardener'])) {
                        echo" All gardeners";
                            $result = executePlainSQL("select m.empno, m.company, m.mname
                                                        from Maintenance m 
                                                        join gardener g on g.empno = m.empno 
                                                        ");
                    } else if (isset($_POST['allseller'])){

                        echo" Maintenanced Hired by All Sellers";
                            $result = executePlainSQL("select m.empno, m.company, m.mname
                                                        from Maintenance m 
                                                        where not exists (select * 
                                                                           from seller s
                                                                           where not exists (select *
                                                                                            from hire h
                                                                                            where s.sellerid=h.sellerid and h.empno=m.empno))");
                                                        

                    }

                    printResult($result);
                }
                
            disconnectFromDB();

    ?> 


    </body>
</html>
