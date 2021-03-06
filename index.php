<?php 
    require_once 'bootstrap.php';
    include_once 'includes/header.php';

    if(loggedIn())
        $favGenre = $genreRepository->getFavouriteGenreFor($username);
?>
        <div class="container">
            <div id="banner">
                <div class="h1bg"><h1>CARREG MUSIC</h1></div>
            </div>
            <div id="mainContent">
                <h2>5 RANDOM TRACKS <?php if(loggedIn()){ echo ' <span class="usernameD">BASED ON YOUR FAVOURITE GENRE ('.strtoupper($favGenre).')</span>';} ?></h2>
                <div id="top5albums">
                <?php
                    if(loggedIn()){
                        $tracks = $trackService->getAllBasedOnGenreFor($favGenre);

                        $neededTracks = 5;
                        $neededTracks -= sizeof($tracks);

                        foreach($tracks as $track)
                        {
                        ?>
                            <div class="top5albumCover">
                                <a href="<?php echo 'tracks.php?id='.$track->id; ?>"><img class="albumCoverImage" src="css/coverPictures/<?php echo $track->picture; ?>" alt="<?php echo $track->title; ?>"></a>
                                <p class="albumCoverText"><?php echo $track->artists." - ".$track->title; ?></p>
                            </div>
                        <?php
                        }
                        if($neededTracks > 0)
                        {
                            $data = $trackService->getAllRecommendedFor($favGenre, $neededTracks);

                            if(!$data)
                            {
                                echo 'We were unable to find any recommended tracks.';
                            }else{

                            while($row = mysqli_fetch_array($data)){ 
                                $trackTitle = $row['trackTitle']; 
                                $artists = $row['artists']; 
                                $artistCount = $row['artistCount']; 
                                $coverPicture = $row['coverPicture'];
                                $trackID = $row['trackID'];
                        ?>
                            <div class="top5albumCover">
                                <a href="<?php echo 'tracks.php?id='.$trackID; ?>"><img class="albumCoverImage" src="css/coverPictures/<?php echo $coverPicture; ?>" alt="<?php echo $trackTitle; ?>"></a>
                                <p class="albumCoverText"><?php echo $artists." - ".$trackTitle; ?></p>
                            </div>
                        <?php
                            }
                            }
                        }
                    }else{
                        $tracks = $trackService->getRandomTracks(5);

                        if(sizeof($tracks) === 0)
                            echo 'We were unable to get any random tracks.';


                        foreach($tracks as $track)
                        {
                ?>
                    <div class="top5albumCover">
                        <a href="<?php echo 'tracks.php?id='.$trackID; ?>"><img class="albumCoverImage" src="css/coverPictures/<?php echo $coverPicture; ?>" alt="<?php echo $trackTitle; ?>"></a>
                        <p class="albumCoverText"><?php echo $artists." - ".$trackTitle; ?></p>
                    </div>
                <?php
                        }
                    }
                ?>
                </div>
            </div>
            <div id="sidebar">
                <h2>SEARCH</h2>
                <form action="search.php" method="get">
                    <input type="text" name="search" placeholder="album, artist, concert">
                    <input type="hidden" name="criteria" value="artists" />
                    <button id="submit"><img src="css/images/search.png" alt="search"></button>
                </form>
                <h2>UPCOMING CONCERTS</h2>
                <?php
                if(loggedIn()){
                    $data = mysqli_query($database->connection, "SELECT countries.countryName FROM countries INNER JOIN users USING(countryID) WHERE username = '$username' LIMIT 1");

                    $row = mysqli_fetch_array($data);

                    $country = $row['countryName'];
                    $neededConcerts = 3;
                    $data = mysqli_query($database->connection, "SELECT concerts.concertID, concerts.concertName, venues.venueName, countries.countryName
                                                FROM concerts INNER JOIN venues USING (venueID) INNER JOIN countries USING (countryID)
                                                WHERE concertDate > CURDATE() and countryName like '%" . $country . "%'
                                                ORDER BY RAND() 
                                                LIMIT 3");        

                    while($row = mysqli_fetch_array($data)){ 
                        $neededConcerts--;
                        $concertName = $row['concertName']; 
                        $venueName = $row['venueName']; 
                        $countryName = $row['countryName'];
                        $concertID = $row['concertID'];
                    ?>
                    <div class="concertBox">
                        <p class="concertTxt"><a href="concerts.php?id=<?php echo $concertID; ?>"><?php echo $concertName." - ".$venueName." (".$countryName.")" ?></a></p>
                    </div>
                    <?php
                    }

                    if($neededConcerts > 0){
                            
                        $data = mysqli_query($database->connection, "SELECT concerts.concertID, concerts.concertName, venues.venueName, countries.countryName
                                                    FROM concerts INNER JOIN venues USING (venueID) INNER JOIN countries USING (countryID)
                                                    WHERE concertDate > CURDATE() and countryName NOT LIKE '% " . $country . "%'
                                                    ORDER BY RAND() 
                                                    LIMIT " . $neededConcerts);        

                        while($row = mysqli_fetch_array($data)){
                            $concertName = $row['concertName'];
                            $venueName = $row['venueName'];
                            $countryName = $row['countryName'];
                            $concertID = $row['concertID'];
                        ?>
                        <div class="concertBox">
                            <p class="concertTxt"><a href="concerts.php?id=<?php echo $concertID; ?>"><?php echo $concertName." - ".$venueName." (".$countryName.")" ?></a></p>
                        </div>
                        <?php
                        }
                            
                    }
                }else{
                    $concerts = $concertService->getRandomConcerts(3);

                    if(sizeof($concerts) === 0)
                        echo 'We were unable to get any random concerts.';

                    foreach($concerts as $concert)
                    {
                ?>
                <div class="concertBox">
                    <p class="concertTxt"><a href="concerts.php?id=<?php echo $concertID; ?>"><?php echo $concertName." - ".$venueName." (".$countryName.")" ?></a></p>
                </div>
                <?php
                    }
                }
                ?>
                <div class="clear"></div>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>