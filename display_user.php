<?php
$title = 'Photofox - User - ' . $_GET['user'];
$currentPage = '';
require_once('nav.php');
require('./database.php');

// SQL-Abfrage für Benutzerdaten
$user = $_GET['user'];
$query = "SELECT * FROM users WHERE username = '$user'";
$result = $conn->query($query);

// Überprüfen, ob ein Benutzer gefunden wurde
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc(); // Benutzerdaten abrufen

    // Profilbild überprüfen
    $profilePic = !empty($userData['profile_pic']) ? $userData['profile_pic'] : './img/noProfilePic.png';
?>

<body>
    <div id="header">
        <img id="profile-pic" src="<?php echo $profilePic; ?>" alt="Profilbild" />
        <h1>@<?php echo $userData['username']; ?></h1>
        <p><?php echo $userData['biography']; ?></p>
        <p>Beiträge: <?php echo $userData['posts_quantity']; ?> | Follower: <?php echo $userData['followers']; ?></p>
    </div>
    <div id="tabs">
        <button href="#" id="showAll" class="tab">Alles</button>
        <button href="#" id="showImages" class="tab">Bilder</button>
        <button href="#" id="showVideos" class="tab">Videos</button>
        <script src="user-posts-filter.js"></script>
    </div>
    <div id="main-content">
        <!-- Dynamische Anzeige von Beiträgen -->
        <?php
        // SQL-Abfrage für Beiträge des Benutzers
        $userId = $userData['id'];
        $postQuery = "SELECT * FROM posts WHERE user_id = '$userId'";
        $postResult = $conn->query($postQuery);

        // Überprüfen, ob Beiträge gefunden wurden
        if ($postResult->num_rows > 0) {
            while ($post = $postResult->fetch_assoc()) {
                ?>
                <div class="content-box <?php echo $post['type']; ?>">
                    <img class="post-img" src="<?php echo $post['src']; ?>" alt="Beitrag" />
                    <div class="post-date">
                        <span class="material-symbols-rounded">calendar_month</span>
                        <span class="date"><?php echo date('d.m.Y', strtotime($post['posted_at'])); ?></span>
                    </div>
                    <div class="view-post-btn">
                        <span class="material-symbols-rounded">visibility</span>
                        <span class="views"><?php echo $post['views']; ?></span>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "Keine Beiträge gefunden.";
        }
        ?>
    </div>
</body>

<?php
} else {
    echo "Benutzer nicht gefunden.";
}
?>
