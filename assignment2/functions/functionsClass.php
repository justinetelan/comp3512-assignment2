

<?php
session_start();
    
    function browseCountries($connection) {
        
        $dbCountry = new CountriesGateway($connection);
        $dbImg = new ImagesGateway($connection);
        $countryF = $dbCountry -> getFields(Array(0, 2)); // ISO, CountryName
        
        $sql = 'SELECT ' . $countryF . ' FROM ' . $dbCountry -> getFrom() .
                ' JOIN ' . $dbImg -> getFrom() . ' WHERE ' . $dbCountry -> getFields(Array(0)) .
                ' = ' . $dbImg -> getFields(Array(5));
        $cInfo = $dbCountry -> findAllSorted($sql, "both");
        
        // echo '<div class="panel panel-info">';
        echo '<div class="row" style="padding:1.5em;">';
        
        foreach($cInfo as $countries) {
            
            echo '<div class="col-md-3">';
            echo '<li class="list-group-item"><a href="single-country.php?id=' . $countries['ISO'] . '" class="list" value="' . $countries['ISO'] .'">' . $countries['CountryName'] . '</a></li>';
            echo '</div>';
            
        }
        
        echo '</div>';
        // echo '</div>'; // close panel panel-info
    }
    
    function browseUsers($connection) {
        $usersSql = "SELECT UserID, FirstName, LastName FROM Users ORDER BY LastName";
        $dbUser = new UsersGateway($connection);
        $userF = $dbUser -> getFields(Array(0, 1, 10)); // FirstName, LastName, ID
        
        $sql = 'SELECT ' . $userF . ' FROM ' . $dbUser -> getFrom();
        $uInfo = $dbUser -> findAllSorted($sql, "orderBy");
        
        // echo '<div class="panel panel-info">';
        echo '<div class="row" style="padding:1.5em;">';
        
        foreach($uInfo as $users) {
                
            echo '<div class="col-md-3">';
            
            echo '<li class="list-group-item"><a href="single-user.php?id=' . $users['UserID'] . '" class="list" value="' . $users['UserID'] .'">' 
                    . $users['FirstName'] . " " . $users['LastName'] . '</a></li>';
            
            echo '</div>';
            
        }
            
        echo '</div>';
        // echo '</div>';
    }
    
    function browsePosts($connection){
        
        $dbPost = new PostsGateway($connection);
        $dbImg = new ImagesGateway($connection);
        $dbUser = new UsersGateway($connection);
        
        $postF = $dbPost -> getFields(Array(0,1,3, 4)); // PostID, UserID,Posts.Title, Message
        $imgF = $dbImg -> getFields(Array(0, 3, 6)); // ImageID, Description, Path
        $userF = $dbUser -> getFields(Array(0,1,10)); // FirstName, LastName, UserID
        
        $sql = 'SELECT ' . $postF . ', ' . $userF . ','   .$imgF .
                ' FROM ' . $dbPost->getFrom() .
                ' JOIN ' . $dbUser->getFrom() .
                ' ON ' . $dbUser->getFrom() . '.' .$dbUser->getPk() .
                ' = ' .$dbPost ->getFields(Array(1)) .
                ' JOIN ' . $dbImg->getFrom() .
                ' ON ' . $dbImg->getFrom() . '.' .$dbImg->getPk() .
                ' = ' . $dbPost->getFrom() . '.' .$dbPost -> getFields(Array(2));
        
        
        $result = $dbPost -> runQuery($sql, null, 0);
        
         foreach($result as $row) {
             echo '<div class="row">
                   <div class="col-md-4">
                     <img src="images/medium/' .$row['Path'].  '" alt="" class="img-responsive"/>
                   </div>';
                  echo' <div class="col-md-8"> 
                      <h2>' . $row['Title'] . '</h2>';
                      echo 'Posted by <a href="single-user.php?id='.$row['UserID'].'">' . $row['FirstName'] .' ' .$row['LastName'] .' </a><br/>';
                        echo '<span class="pull-right"></span>';
                      
                      echo '<p class="excerpt">'.substr($row['Description'], 0, 150).'</p>
                            <p class="pull-left"><a href="single-post.php?id=' . $row['PostID'] . '" class="btn btn-primary btn-sm">Read more</a></p>
                   </div>
               </div>
               <hr/>';
         }
    }
    
    function viewFaves() {
        
        $user = $_SESSION['first'];// . ' ' . $_SESSION['last'];
        
        // if(isset($_SESSION['faveP'])){
        // echo' <div class="alert alert-success" id="favePost" role="alert" style="visibility:hidden">';
        //      echo'  ITEM ADDED TO FAVOURITES';
        //      echo' </div>';
        //      
        
        // }
        
        // if(isset($_SESSION['faveI'])){
        // echo' <div class="alert alert-success" id="faveImage" role="alert" style="visibility:hidden">';
        //      echo'   IMAGE ADDED TO FAVOURITES';
        //      echo' </div>';
        
        // }
        
        echo '<h2>Welcome to your favourites list, ' . $user . '.' . '</h2>';
        
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        
        if(count($_SESSION['faveImg']) != 0) { 
            echo '<h3>Images List</h3>';
        } else {
            echo '<h3>No favourite images</h3>';
        }
         
        
        ?>
        <script>
        var a = document.getElementById("favePost");
        
            a.style="visibility: visible";
            
            setTimeout(function() {
            a.style="visibility: hidden";
            }, 2000);
        
        </script>
        
        <?php
        
        foreach($_SESSION['faveImg'] as $img) {
          
            echo '<div>';
            echo 'Title: ' . $img['Title'] . '<br>';
            echo '<a href="single-image.php?id=' . $img['ImageID'] . '"><img src="images/square-small/' . $img['Path'] . '" alt="Favourite Image"></a><br>';
            echo '<a href="rmvFaveImg.php?id=' . $img['ImageID'] . '"><button name="rmv" type="submit" class="btn btn-info btn-xs">Remove</button></a>';
            echo '</div>';
        }
        
        if(count($_SESSION['faveImg']) != 0) {
            echo '<a href="rmvAllImg.php?id=0"><button name="rmv" type="submit" class="btn btn-warning btn-xs">Remove All Favourite Images</button></a>';    
        }
        
        echo '</div>'; // close col-md-6 for IMAGES
        
        echo '<div class="col-md-6">';
        
        if(count($_SESSION['favePost']) != 0) { 
            echo '<h3>Posts List</h3>';
        } else {
            echo '<h3>No favourite posts</h3>';
        }
        
        foreach($_SESSION['favePost'] as $post) {
            echo '<div>';
            echo 'Title: ' . $post['Title'] . '<br>';
            echo '<a href="single-post.php?id=' . $post['PostID'] . '"><img src="images/square-small/' . $post['Path'] . '" alt="Favourite Image"></a><br>';
            echo '<a href="rmvFavePost.php?id=' . $post['PostID'] . '"><button name="rmv" type="submit" class="btn btn-info btn-xs">Remove</button></a>';
            echo '</div>';
        }
        
        if(count($_SESSION['favePost']) != 0) {
            echo '<a href="rmvAllImg.php?id=1"><button name="rmv" type="submit" class="btn btn-warning btn-xs">Remove All Favourite Images</button></a>';    
        }
        
        echo '</div>'; // close col-md-6 for POSTS
        
        echo '</div>'; // close row
        
    
    }
    
     function addFavePost($connection, $faveType) {//, $arry) {
        
        if($faveType == "singleImg") {
            echo "<a href='addFaveImg.php?id=" . $_GET['id'] . "'<button type='button' class='btn btn-default'><span class='glyphicon glyphicon-heart' aria-hidden='true'></span></button></a>";
            
        } else if($faveType == "singlePost") {
            echo "<a href='addFavePost.php?id=" . $_GET['id'] . "'<button type='button' class='btn btn-default'><span class='glyphicon glyphicon-heart' aria-hidden='true'></span></button></a>";    
        }
        
    }
    
    function singlePost($connection) {
        
        $dbPost = new PostsGateway($connection);
        $dbImg = new ImagesGateway($connection);
        $dbUser = new UsersGateway($connection);
        
        $postF = $dbPost -> getFields(Array(2, 3, 4, 5)); // MainPostImage, Title, Message, PostTime
        $imgF = $dbImg -> getFields(Array(0, 6)); // UserID, Path
        $userF = $dbUser -> getFields(Array(0, 1, 10)); // FirstName, LastName
        
        $sql = 'SELECT ' . $postF . ', ' . $imgF . ', ' . $userF .
                ' FROM ' . $dbPost -> getFrom() . ', ' . $dbImg -> getFrom() . ', ' . $dbUser -> getFrom() .
                ' WHERE ' . $dbPost -> getFrom() . '.' . $dbPost -> getFields(Array(2)) . ' = ' . $dbImg -> getFrom() . '.' . $dbImg -> getPk() .
                ' AND ' . $dbPost -> getFields(Array(1)) . ' = ' . $dbUser -> getFrom() . '.' . $dbUser -> getPk() .
                ' AND ';
        
        $result = $dbPost -> getById($sql, $_GET['id'], 0);
        
        // echo $sql;
        
        echo "<div class='col-md-8'>";
        
        echo "<img class='img-responsive' ";
        
        echo "src='images/medium/" . $result['Path'] . "' alt='" . $result['Title'] . "'>";
        echo "<p>" . $result['Message'] . "</p>";
    
        echo "</div>"; // close col-md-8
        
        echo "<div class='col-md-4'>";
        
        echo "<h1>" . $result['Title'] . "</h1>";
        
        echo "<div class='panel panel-default'>";
            echo "<div class='panel-body'>";
                echo "<ul class='details-list'>";
                    echo "<li>By: <a href='single-user.php?id=" . $result['UserID'] . "'>" . $result['FirstName'] . " " . $result['LastName'] . "</a></li>";
                    echo "<li>" . $result['PostTime'] . "</li>";
                echo "</ul>";
                relatedImgPost($connection);
            echo "</div>";
        echo "</div>";
        
    }
    
    function relatedImgPost($connection) {
        
        $dbPost = new PostsGateway($connection);
        $dbImg = new ImagesGateway($connection);
        $dbPostImg = new PostImagesGateway($connection);
        
        
        $imgF = $dbImg -> getFields(Array(0, 6)); // ImageID, Path
        $imgID = $dbPostImg -> getFrom() . '.' . $dbPostImg -> getFields(Array(0));
        $postID = $dbPostImg -> getFrom() . '.' . $dbPostImg -> getFields(Array(1));
        
        
        $sql = 'SELECT ' . $imgF . ', ' . $imgID . ', ' . $postID .
                ' FROM ' . $dbImg -> getFrom() . ' JOIN ' . $dbPostImg -> getFrom() .
                ' USING(' . $dbPostImg -> getFields(Array(0)) . ') ' .
                ' WHERE ' . $dbPostImg -> getFrom() . '.';
                
        $result = $dbPost -> getById($sql, $_GET['id'], 1);
        
        foreach($result as $row) {
            
            // echo '<div id="smallImg">';
              echo '<a href="single-image.php?id=' . $row['ImageID'] . '">
                    <img src="images/square-small/' . $row['Path'] . '"></a>';
            // echo '</div>';
            
        }   
        
    }
    
    function singleUser($connection) {
        
        $dbUser = new UsersGateway($connection);
        // FirstName, LastName, Address, City, Country, Postal, Phone, Email
        $userF = $dbUser -> getFields(Array(0, 1, 2, 3, 5, 6, 7, 8)); 
        
        $sql = 'SELECT ' . $userF . ' FROM ' . $dbUser -> getFrom() . ' WHERE ';
        $users = $dbUser -> getById($sql, $_GET['id'], 0);
        
        echo "<h3>" . $users['FirstName'] . " " . $users['LastName'] . "</h3>";
        echo "<p>" . $users['Address'] . "</p>";
        echo "<p>" . $users['City'] . ", " . $users['Postal'] . ", " . $users['Country'] . "</p>";
        echo "<p>" . $users['Phone'] . "</p>";
        echo "<p>" . $users['Email'] . "</p>";
    }
    
    function singleImg($connection) {
        
        $dbImg = new ImagesGateway($connection);
        $mainImg = 'SELECT ' . $dbImg -> getFields(Array(2, 3, 6)) . ' FROM ' . $dbImg -> getFrom() . ' WHERE '; // Title, Description, Path
        $image = $dbImg -> getById($mainImg, $_GET['id'], 0);
        // echo $mainImg;
        // echo $image;
        
        echo "<div class='col-md-8'>";
        
        mapp($connection, "country");
        
        echo "<img class='img-responsive' ";
        
        echo "src='images/medium/" . $image['Path'] . "' alt='" . $image['Title'] . "'>";
        echo "<p class='description'>" . $image['Description'] . "</p>";
    
        echo "</div>"; // close col-md-8
        
        sideInfo($connection);
        
    }
    
    function sideInfo($connection) {
        
        $dbImg = new ImagesGateway($connection); $dbUser = new UsersGateway($connection);
        $dbCount = new CountriesGateway($connection); $dbCity = new CitiesGateway($connection);
        
        $imgF = $dbImg -> getFields(Array(2)); $userF = $dbUser -> getFields(Array(0, 1, 10)); // Title, FirstName, LastName, UserID
        $countF = $dbCount -> getFields(Array(0, 2)); $cityF = $dbCity -> getFields(Array(0)); // ISO, CountryName, AsciiName
        
        $info = 'SELECT ' . $imgF . ', ' . $userF . ', ' . $countF . ', ' . $cityF .
                ' FROM ' . $dbImg -> getFrom() . ', ' . $dbUser -> getFrom() . ', ' . $dbCount -> getFrom() . ', ' . $dbCity -> getFrom() .
                ' WHERE ' . $dbImg -> getFields(Array(1)) . ' = ' . $dbUser -> getFields(Array(10)) .
                ' AND ' . $dbImg -> getFields(Array(5)) . ' = ' . $dbCount -> getFields(Array(0)) .
                ' AND ' . $dbImg -> getFrom() . '.' . $dbImg -> getFields(Array(4)) . ' = ' . $dbCity -> getFields(Array(7)) . ' AND ';
        $sideInfo = $dbImg -> getById($info, $_GET['id'], 0);
        // echo $info;
        
        echo "<div class='col-md-4'>";
        
        echo "<h2>" . $sideInfo['Title'] . "</h2>";
        
        echo "<div class='panel panel-default'>";
            echo "<div class='panel-body'>";
                echo "<ul class='details-list'>";
                    echo "<li>By: <a href='single-user.php?id=" . $sideInfo['UserID'] . "'>" . $sideInfo['FirstName'] . " " . $sideInfo['LastName'] . "</a></li>";
                    echo "<li>Country: <a href='single-country.php?id=" . $sideInfo['ISO'] . "'>" . $sideInfo['CountryName'] . "</a></li>";
                    echo "<li>City: " . $sideInfo['AsciiName'] . "</li>";
                echo "</ul>";
            echo "</div>";
        echo "</div>"; // close col-md-4
        
    }
    
    function countryInfo($connection) {
        $dbCountry = new CountriesGateway($connection);
        
        //CountryName, Capital, Area, Population, CurrencyName, CountryDescription
        $countryF = $dbCountry -> getFields(Array(2, 3, 5, 6, 10, 14));
        
        $sqlInfo = 'SELECT ' . $countryF . ' FROM ' . $dbCountry -> getFrom() . ' WHERE ';
        
        $countryInfo = $dbCountry -> getById($sqlInfo, $_GET['id'], 0);
        
        echo '<h3>' . $countryInfo['CountryName'] . '</h3><hr>';
        
        mapp($connection, "country");
        
        echo '<br><p><strong>Capital: </strong>' . $countryInfo['Capital'] . '</p>';
        echo '<p><strong>Area: </strong>' . number_format($countryInfo['Area']) . '</p>';
        echo '<p><strong>Population: </strong>' . number_format($countryInfo['Population']) . '</p>';
        echo '<p><strong>Currency Name: </strong>' . $countryInfo['CurrencyName'] . '</p>';
        echo '<p>' . $countryInfo['CountryDescription'] . '</p>';

    }
    
    function singleHeader($connection, $type) {
        
        echo '<div class="panel panel-info">';
        
        if($type == "countries") {
            
            $dbCount = new CountriesGateway($connection);
            $countF = $dbCount -> getFields(Array(2)); // CountryName
            $sqlCount = 'SELECT ' . $countF . ' FROM ' . $dbCount -> getFrom() . ' WHERE ';
            echo $sqlCount;
            $countInfo = $dbCount -> getById($sqlCount, $_GET['id'], 0);
            
            echo '<div class="panel-heading">Images from ' . $countInfo['CountryName'] . '</div>';
            
        } else if($type == "users") {
            
            $dbUse = new UsersGateway($connection);
            $useF = $dbUse -> getFields(Array(0,1)); // FirstName, LastName
            $sqlUse = 'SELECT ' . $useF . ' FROM ' . $dbUse -> getFrom() . ' WHERE ';
            // echo $sqlUse;
            $useInfo = $dbUse -> getById($sqlUse, $_GET['id'], 0);
            echo '<div class="panel-heading">Images from ' . $useInfo['FirstName'].' ' . $useInfo['LastName']. '</div>';
            
        }
        
        identifyType($connection, $type);
        
    }
    
    // calls another function to show appropriate images via query string for single page CHANGE INTO Gateways
    function identifyType($connection, $type) {
        
        $object = "";
        
        if($type == "countries") {
            
            $dbCnty = new CountriesGateway($connection);
            $dbIma = new ImagesGateway($connection);
            
            $cntyF = $dbCnty -> getFields(Array(2)); // CountryName
            $imaF = $dbIma -> getFields(Array(0,2,6)); // ImageID, Title, Path
            $sql1 = 'SELECT ' . $cntyF . ', ' . $imaF . ' FROM ' . $dbCnty -> getFrom() . 
            ' JOIN ' . $dbIma -> getFrom() .
            ' WHERE ' . $dbCnty -> getFields(Array(0)) . 
            ' = ' . $dbIma -> getFields(Array(5)) . ' AND '; 
            // echo $sql1 . '<br>';
            $object = $dbCnty -> getById($sql1, $_GET['id'], 1);
            
                                     
        } else if($type == "users") {
            
        'SELECT Users.UserID, FirstName, LastName, Path, ImageID FROM Users JOIN ImageDetails
                    WHERE Users.UserID = ImageDetails.UserID AND Users.UserID =?';
            
            $dbUs = new UsersGateway($connection);
            $dbIma = new ImagesGateway($connection);
            
            $usF = $dbUs -> getFields(Array(0,1,10)); // FirstName, LastName, Users.UserID
            $imaF = $dbIma -> getFields(Array(0,2,6)); // ImageID, Title, Path
            $sql2 = 'SELECT ' . $usF . ', ' . $imaF . ' FROM ' . $dbUs -> getFrom() . 
            ' JOIN ' . $dbIma -> getFrom() .
            ' WHERE ' . $dbUs -> getFields(Array(10)) .
            ' = ' . $dbIma -> getFields(Array(1)) . ' AND ' . $dbUs -> getFrom() . '.';
            
            // echo $sql2;
            
            $object = $dbUs -> getById($sql2, $_GET['id'], 1);
            
        }
        
        
        showImg("singles", $object); 
        echo '</div>'; // close panel-info
    }
    
    function dropdown($connection, $type) {
        
        if($type == "continent") {
            
            $dbCont = new ContinentsGateway($connection);
            $contF = $dbCont -> getFields(Array(0, 1)); // ContinentCode, ContinentName
            
            $sqlCont = 'SELECT ' . $contF . ' FROM ' . $dbCont -> getFrom();
            $result = $dbCont -> findAllSorted($sqlCont, "orderBy"); // ORDER BY ContinentName
            
            foreach($result as $conti) {
                echo '<option value="' . $conti['ContinentCode'] . '">' . $conti['ContinentName'] . '</option>';
            }
            
            
        } else if($type == "country") {
            
            $dbCount = new CountriesGateway($connection);
            $dbImg = new ImagesGateway($connection);
            $countF = $dbCount -> getFields(Array(0, 2)); // Countries.ISO, CountryName
            
        
            $sqlCount = 'SELECT ' . $countF . ' FROM ' . $dbCount -> getFrom() .
                        ' JOIN ' . $dbImg -> getFrom() .
                        ' WHERE ' . $dbCount -> getFields(Array(0)) . ' = ' . $dbImg -> getFields(Array(5));
            $result = $dbCount -> findAllSorted($sqlCount, "groupBy"); // GROUP BY ISO
            
            foreach($result as $cntry) {
                echo '<option value="' . $cntry['ISO'] . '">' . $cntry['CountryName'] . '</option>';
            }
            
        } else if($type == "city") {
                          
            $dbCity = new CitiesGateway($connection);
            $dbImg = new ImagesGateway($connection);
            $cityF = $dbCity -> getFields(Array(0, 7)); // AsciiName, Cities.CityCode
            
            $sqlCity = 'SELECT ' . $cityF . ' FROM ' . $dbCity -> getFrom() .
                        ' JOIN ' . $dbImg -> getFrom() .
                        ' WHERE ' . $dbCity -> getFields(Array(7)) . ' = ' . 
                        $dbImg -> getFrom() . '.' . $dbImg -> getFields(Array(4));
            $result = $dbCity -> findAllSorted($sqlCity, "both"); // ORDER BY AsciiName
            
            foreach($result as $city) {
                echo '<option value="' . $city['CityCode'] . '">' . $city['AsciiName'] . '</option>';
            }
        }
        
    }
    
    function filterHeader($connection) {
        
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';//Images ';
        
        if(isset($_GET['imgTitle']) && $_GET['imgTitle'] != "") {
            
            echo "Title = " . $_GET['imgTitle'];
            
        } else if((!isset($_GET['continent']) && !isset($_GET['country']) && !isset($_GET['city'])) || ($_GET['country'] == "0" && $_GET['continent'] == "0" && $_GET['city'] == "0")) {
                
            echo "All Images";
            
        } else if(isset($_GET['continent']) && $_GET['continent'] != "0") {
            
            $dbCont = new ContinentsGateway($connection);
            $contF = $dbCont -> getFields(Array(1)); // ContinentName
            $sql = 'SELECT ' . $contF . ' FROM ' . $dbCont -> getFrom() . ' WHERE '; 
            $continent = $dbCont -> getById($sql, $_GET['continent'], 0);
            echo "Continent = " . $continent['ContinentName'];
            
        } else if(isset($_GET['country']) && $_GET['country'] != "0") {
            
            $dbCount = new CountriesGateway($connection);
            $countF = $dbCount -> getFields(Array(2)); // CountryName
            $sql = 'SELECT ' . $countF . ' FROM ' . $dbCount -> getFrom() . ' WHERE '; 
            $country = $dbCount -> getById($sql, $_GET['country'], 0);
            echo "Country = " . $country['CountryName'];
        
        } else if(isset($_GET['city']) && $_GET['city'] != "0") {
            
            $dbCity = new CitiesGateway($connection);
            $cityF = $dbCity -> getFields(Array(0)); // AsciiName
            $sql = 'SELECT ' . $cityF . ' FROM ' . $dbCity -> getFrom() . ' WHERE '; 
            $city = $dbCity -> getById($sql, $_GET['city'], 0);
            echo "City = " . $city['AsciiName'];
            
        } 
        
        echo '</div>';
        
            echo '<div class="panel-body">';
            
                filterImg($connection); // calls to retrieve what has been filtered
                
            echo '</div>';
            
        echo '</div>'; // close panel-default
        
    }
    
    function filterImg($connection) {
        
        if($_SERVER["REQUEST_METHOD"] == "GET") {
            
            $dbImg = new ImagesGateway($connection);
            $startSql = 'SELECT ' . $dbImg -> getFields(Array(0, 2, 6)) . ' FROM '; // ImageID, Title, Path
            $sql = ""; $result = "";
            
            if(isset($_GET['imgTitle']) && $_GET['imgTitle'] != "") {
                
                $search = $_GET['imgTitle'];
                $search = '"%' . $search . '%"';
                $sql = $startSql . ' ImageDetails WHERE Title LIKE ' . $search;
                $result = $dbImg -> runQuery($sql, null, 0);
                
            } else if((!isset($_GET['continent']) && !isset($_GET['country']) && !isset($_GET['city'])) || ($_GET['country'] == "0" && $_GET['continent'] == "0" && $_GET['city'] == "0" && $_GET['imgTitle'] == "")) {
                
                $sql = $startSql . $dbImg -> getFrom();
                // echo $sql;
                $result = $dbImg -> runQuery($sql, null, 0);
                
            } else if(isset($_GET['continent']) && $_GET['continent'] != "0") {
                
                $dbCont = new ContinentsGateway($connection);
                $sql = $startSql . $dbCont -> getFrom() . ' JOIN ' . $dbImg -> getFrom() .
                        ' USING(' . $dbCont -> getFields(Array(0)) . ')' .
                        ' WHERE ' . $dbCont -> getFrom() . '.';
                        
                $result = $dbCont -> getById($sql, $_GET['continent'], 1);
                // echo $sql;
                
            } else if(isset($_GET['country']) && $_GET['country'] != "0") {
             
                $dbCount = new CountriesGateway($connection);
                $sql = $startSql . $dbCount -> getFrom() . ' JOIN ' . $dbImg -> getFrom() .
                        ' WHERE ' . $dbCount -> getFields(Array(0)) . ' = ' . $dbImg -> getFields(Array(5)) . ' AND ';
                $result = $dbCount -> getById($sql, $_GET['country'], 1);
                // echo $sql;
                
            } else if(isset($_GET['city']) && $_GET['city'] != "0") {
                
                $dbCity = new CitiesGateway($connection);
                $sql = $startSql . $dbCity -> getFrom() . ' JOIN ' . $dbImg -> getFrom() .
                        ' USING(' . $dbCity -> getPk() . ')' .
                        ' WHERE ';
                // echo '<br>' . $sql;
                
                $result = $dbCity -> getById($sql, $_GET['city'], 1);
                // echo $sql;
               
            } 
            
            showImg("filtering", $result);
            
        }
        
    }   
        
    function showImg($page, $object) {
        
        foreach($object as $img) {
            
            if($page == "singles") {
               
                echo '<div class="smallImg" onmouseover="popIn('.$img['ImageID'].')" onmouseout="popOut('.$img['ImageID'].')">';
                    
                    echo '<a href="single-image.php?id=' . $img['ImageID'] . '"><img src="images/square-small/' . $img['Path'] . '"></a>';
                
                echo '</div>'; // close images div 
                
                 echo '
                  
                <div class="popS"  id='.$img['ImageID'].' >';// popover small image
                    echo '<h4>'.$img['Title'].'</h4>';
                    echo '<img src="images/medium/' . $img['Path'] . '" >';
                
                echo '
                </div>'; 
                
                ?>
                <script>
                    function popIn(c){
                        var x = event.clientX;
                        var y = event.clientY ;
                        
                        var v = Number(x) - 650;
                        var z = Number(y) - 80;
                        var snowball = document.getElementById(c);
                         snowball.style.visibility="visible";
                        snowball.style.position = "absolute";
                        snowball.style.right = v + 'px';
                        snowball.style.top = z + 'px';
                        
                    }
                    function popOut(p){
                        document.getElementById(p).style.visibility="hidden";
                    }
                </script>
            
             <?php   
               
            } else if($page == "filtering") {
                
                echo '<li>';
                echo '<a href="single-image.php?id=' . $img['ImageID'] . '"><img src="images/square-medium/' . $img['Path'] . '" class="img-circle" >';
                echo '<div class="caption">';
                    echo '<div class="blur"></div>';
                        echo '<div class="caption-text">';
                            echo '<p>' . $img['Title'] . '</p>';
                        echo '</div>'; // close caption-text
                echo '</div></a>'; // close caption
                echo '</li>';
                
            }
            
        
        } // close loop
    
    
    }
    
    function mapp($connection, $type){
        
        $dbCoun = new CountriesGateway($connection);
        $dbIm = new ImagesGateway($connection);
        $counInfo = $dbCoun -> getFields(Array(0,2)); // ISO
        $imInfo = $dbIm -> getFields(Array(5,7,8)); // CountryCodeISO, Longtitude, Latitude
        
        $sqlCoun = ' SELECT ' . $imInfo . ', ' . $counInfo . ' FROM ' . $dbIm -> getFrom() . 
                    ' JOIN ' . $dbCoun->getFrom() . ' ON ' . $dbCoun -> getFields(Array(0)) .
                     ' = ' . $dbIm -> getFields(Array(5)) . ' WHERE ';
        
        if($type == "country") {
            $coordinates = $dbCoun -> getById($sqlCoun, $_GET['id'], 0);
        } else if($type == "image") {
            $coordinates = $dbIm -> getById($sqlCoun, $_GET['id'], 0);
        }
                    
        $lat = $coordinates['Latitude'];
        $long = $coordinates['Longitude'];
        
        echo '<br>';
        
        // call script here <script> initMap() </script>
        // echo '<script> initMap() </script>';
        
        // echo '<script 
        //         src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8izLDbgoSU5XQQhwjGI_c3L1OnnJ1lkU&callback=initMap">
        //     </script>';
                       
                        
                        
?>
    <script>
    
    function initMap() {
            document.write("<div id='map' style='width:95%;height:400px;'></div>");
            var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 4,
              center: uluru
            });
            var marker = new google.maps.Marker({
              position: uluru,
              map: map
            });
    }
    </script>
    
    <script 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8izLDbgoSU5XQQhwjGI_c3L1OnnJ1lkU&callback=initMap">
    </script>
    <?php
    } // close mapp function
    
    function userProfile() {
        echo '<h3>Hello, '. $_SESSION['first'] . ' ' . $_SESSION['last'] .'</h3>';
        echo '<hr><h4>Here is your information</h4>';
        echo '<p><strong>Address: </strong>' . $_SESSION['address'] . '</p>';
        echo '<p><strong>City: </strong>' . $_SESSION['city'] . '</p>';
        echo '<p><strong>Region: </strong>' . $_SESSION['region'] . '</p>';
        echo '<p><strong>Postal: </strong>' . $_SESSION['postal'] . '</p>';
        echo '<p><strong>Phone: </strong>' . $_SESSION['phone'] . '</p>';
        echo '<p><strong>Email: </strong>' . $_SESSION['email'] . '</p>';
    }
    
?>