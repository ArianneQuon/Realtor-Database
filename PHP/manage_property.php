
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
        <title>Manage Properties</title>
    </head>

    <body>
    <a href="realtor_home.php">
            <button type="button">Back</button>  
        </a>
        
        <header>
            <h1>Manage Properties</h1>
        </header>

        <fieldset>
            <!-- can currently choose only one :(( -->
            <legend>Type of Property:</legend>
            <form method="post" action="manage_property.php">
            <div>
                <input type="checkbox" id="office" name="office" >
                <label for="office">Office Building</label>
            </div>
            <div>
                <input type="checkbox" id="house" name="house" >
                <label for="house">House</label>
            </div>
            <div>
                <input type="checkbox" id="apt" name="apt" >
                <label for="apt">Apartment</label> <br>
                <input type="submit" id="submit" name="submit" value="Submit">
            </div>
        </form>
        </fieldset>

        <fieldset>
            <legend> Determine the (choose one): </legend>
            <form method="get" action="manage_property.php">
            <input type="hidden" id="aggregate" name="aggregate">
            <div>
                <input type="checkbox" id="ag" name="ag">
                <label for="ag">Average BuyPrice Per Sqft Group</label><br>
            </div> 
            <div>
                <input type="checkbox" id="ag_having" name="ag_having" >
                <label for="ag_having">Average BuyPrice Per Sqft Group with more than 1 in the Group</label>
            </div>     
            <div>
                <input type="checkbox" id="nested_ag" name="nested_ag">
                <label for="nested_ag">Get Highest Average BuyPrice Per Sqft Group</label><br>
            </div>  
            
            <input type="submit" id="aggregate" name="aggregate">
        </form>
        </fieldset>
        <!-- <br><form name="edit" method="POST" action="property_edit.php">
            <input type="hidden" id="edit" name="edit">
            <b>Edit A Property</b>
            <input type="submit" id = "edit" value="Edit">
        </form> -->
        <a href="edit_property.php">
            <button type="button">Edit/Remove A Property</button>  
        </a>
        <a href="new_property.php">
            <button type="button">Make A New Listing</button>  
        </a><br>
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

  function printOfficeResult($result) { //prints results from a select statement

      echo "<table>";
      echo "<tr>
          <th>ID</th>
          <th>Address</th>
          <th>Square Feet</th>
          <th>Number of Floors</th>
      </tr>";

      while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] ."</td><td>". $row[3] . "</tr>"; //or just use "echo $row[0]"
      }

      echo "</table>";
  }
  
  function printApartmentResult($result) { //prints results from a select statement

    echo "<table>";
    echo "<tr>
        <th>ID</th>
        <th>Address</th>
        <th>Square Feet</th>
        <th>Number of Rooms</th>
    </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] ."</td><td>". $row[3] . "</tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}
function printHouseResult($result) { //prints results from a select statement

    echo "<table>";
    echo "<tr>
        <th>ID</th>
        <th>Address</th>
        <th>Square Feet</th>
        <th>Number of Bedrooms</th>
        <th>Number of Bathrooms</th>
    </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>"
        . $row[2] ."</td><td>". $row[3] . "</td><td> " . $row[4] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function printNumResult($result) {
    echo "<table>";
    echo "<tr>
        <th>Square Feet</th>
        <th>Avg(BuyPrice)</th>
    </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1]. "</td></tr>"; //or just use "echo $row[0]"
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
            if (array_key_exists('ag', $_GET)) {
                $result = executePlainSQL("select sqft, avg(buyprice) from property p group by sqft");
            } else if (array_key_exists('ag_having', $_GET)) {
                $result = executePlainSQL("select sqft, avg(buyprice) from property p group by sqft having count(*)>1");
            } else if (array_key_exists('nested_ag', $_GET)) {
                $result = executePlainSQL("select sqft, avg(buyprice) from property p group by sqft 
                                        having avg(buyprice) >= all (select avg(buyprice) from property p group by buyprice)");
            }

            printNumResult($result);
        }  

        if ($db_conn) {  
            // attempting to get both checkboxes and submit buttons to work on buyer to select fields
               if (isset($_POST['office']) && isset($_POST['apt']) && isset($_POST['house'])) {
                        echo"Currently viewing offices";
                        $result = executePlainSQL("select o.propertyid, p.propertyaddress, p.sqft, o.numfloors
                    from officebuilding o join property p 
                    on p.propertyid=o.propertyid");
                        printOfficeResult($result);
    
                        echo"Currently viewing apartments";
                        $result = executePlainSQL("select a.propertyid, p.propertyaddress, p.sqft, a.numrooms 
                    from apartment a join property p 
                    on p.propertyid=a.propertyid");
                        printApartmentResult($result);
    
                        echo"Currently viewing houses";
                        $result = executePlainSQL("select h.propertyid, p.propertyaddress, p.sqft, h.numbed, h.numbath 
                    from house h join property p 
                    on p.propertyid=h.propertyid");
                        printHouseResult($result);
    
                    } else if (isset($_POST['office']) && !isset($_POST['apt']) && isset($_POST['house'])) {
                        echo"Currently viewing offices";
                        $result = executePlainSQL("select o.propertyid, p.propertyaddress, p.sqft, o.numfloors
                    from officebuilding o join property p 
                    on p.propertyid=o.propertyid");
                        printOfficeResult($result);
    
                        echo"Currently viewing houses";
                        $result = executePlainSQL("select h.propertyid, p.propertyaddress, p.sqft, h.numbed, h.numbath 
                    from house h join property p 
                    on p.propertyid=h.propertyid");
                        printHouseResult($result);
    
                    } else if (isset($_POST['office']) && isset($_POST['apt']) && !isset($_POST['house'])) {
                        echo"Currently viewing offices";
                        $result = executePlainSQL("select o.propertyid, p.propertyaddress, p.sqft, o.numfloors
                    from officebuilding o join property p 
                    on p.propertyid=o.propertyid");
                        printOfficeResult($result);
    
                        echo"Currently viewing apartments";
                        $result = executePlainSQL("select a.propertyid, p.propertyaddress, p.sqft, a.numrooms 
                    from apartment a join property p 
                    on p.propertyid=a.propertyid");
                        printApartmentResult($result);
    
                    } else if (!isset($_POST['office']) && isset($_POST['apt']) && isset($_POST['house'])) {
                        echo"Currently viewing apartments";
                        $result = executePlainSQL("select a.propertyid, p.propertyaddress, p.sqft, a.numrooms 
                    from apartment a join property p 
                    on p.propertyid=a.propertyid");
                        printApartmentResult($result);
    
                        echo"Currently viewing houses";
                        $result = executePlainSQL("select h.propertyid, p.propertyaddress, p.sqft, h.numbed, h.numbath 
                    from house h join property p 
                    on p.propertyid=h.propertyid");
                        printHouseResult($result);
    
                    } else if (isset($_POST['office'])) {
                        echo"Currently viewing offices";
                        $result = executePlainSQL("select o.propertyid, p.propertyaddress, p.sqft, o.numfloors
                    from officebuilding o join property p 
                    on p.propertyid=o.propertyid");
                        printOfficeResult($result);
    
                    } else if (isset($_POST['apt'])) {
                        echo"Currently viewing apartments";
                        $result = executePlainSQL("select a.propertyid, p.propertyaddress, p.sqft, a.numrooms 
                    from apartment a join property p 
                    on p.propertyid=a.propertyid");
                        printApartmentResult($result);
    
                    } else if (isset($_POST['house'])) {
                        echo"Currently viewing houses";
                        $result = executePlainSQL("select h.propertyid, p.propertyaddress, p.sqft, h.numbed, h.numbath 
                    from house h join property p 
                    on p.propertyid=h.propertyid");
                        printHouseResult($result);
                    }
                }
  ?>
    </body>
</html>
