<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    header("location: ../");
    exit();
}
$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];
if ($userdata['status'] == 0) {
    $status = '<b style="color:red">Not Voted</b>';
} else {
    $status = '<b style="color:green">Voted</b>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Voting System Registration</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>
<body>
    <header>
        <a href="../"><button>Back</button></a>
        <button><a href="../api/logout.php">Logout</a></button>
        <h1>Online Voting System</h1>
    </header>
    <hr>
    <div id="Profile">
        <img src="../uploads/<?php echo $userdata['photo']; ?>" height="200" width="200">
        <b>Name:</b> <?php echo $userdata['name']; ?><br><br>
        <b>Mobile:</b> <?php echo $userdata['mobile']; ?><br><br>
        <b>Address:</b> <?php echo $userdata['address']; ?><br><br>
        <b>Status:</b> <?php echo $status; ?><br><br>
    </div>
    <div id="Group">
        <?php
        if ($groupsdata) {
            for ($i = 0; $i < count($groupsdata); $i++) {
                ?>
                <div>
                    <img style="float:right;" src="../uploads/<?php echo $groupsdata[$i]['photo']; ?>" height="200" width="200"><br><br>
                    <b>Group Name: <?php echo $groupsdata[$i]['name']; ?></b><br><br>
                    <b>Votes: <?php echo $groupsdata[$i]['votes']; ?></b><br><br>
                    <form action="../api/vote.php" method="post">
                        <input type="hidden" name="gvotes" value="<?php echo $groupsdata[$i]['votes']; ?>">
                        <input type="hidden" name="gid" value="<?php echo $groupsdata[$i]['id']; ?>">
                        <?php if ($userdata['status'] == 0) { ?>
                            <input type="submit" name="votebtn" value="Vote" id="votebtn">
                        <?php } else { ?>
                            <input type="submit" name="votebtn" value="Vote" id="votebtn" disabled>
                        <?php } ?>
                    </form>
                </div>
                <?php
            }
        }
        ?>
    </div>
</body>
</html>
