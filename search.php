<?php include('includes/header.php');
// include('includes/handlers/includeFiles.php');

    if(isset($_GET['term'])) {
        $searchTerm = urldecode($_GET['term']);
    } else {
        $searchTerm = "";
    }

?>
    <div class="search-box">
        <h2>Search for artists, albums and songs</h2>
        <input type="text" value='<?php echo $searchTerm; ?>' class='search-input' placeholder="Type here...">
    </div>

<?php include('includes/footer.php'); ?>