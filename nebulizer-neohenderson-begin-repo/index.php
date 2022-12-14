<!DOCTYPE html>

<html>

<head>
<?php $title = "Nebulizer" ?>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/nebulize.css">
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/solid.css" rel="stylesheet">
    <link href="fontawesome/css/regular.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@200..900">
</head>
<body>

<?php if(isset($_SESSION['message'])): ?>
<div id="notifPop" class="message msg-<?php echo $_SESSION['msg_type']; ?>">
    <?php 
    echo $_SESSION['message']; 
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
    ?>

</div>
<?php endif; ?>

<section id="applet" class="applet">
<?php require_once 'process.php' ?>

<header class="glassPlate">
<h2 class="appletPage">Your Groups</h2>
<nav>
    <button>Nav1</button>
    <button>Nav2</button>
</nav>
</header>


<section id="groupList" class="groupList"> <!-- Display list of all groups in table & Button for New -->
<?php include 'views/mysqliStart.php';?>
<?php $result = $mysqli->query("SELECT * FROM nebula_groups") or die($mysqli->error); ?>

    <div class="groupsContainer">
    <?php if (mysqli_num_rows($result) == 0) {
    echo "You have no groups yet.";
    } else {
    while($row = $result->fetch_assoc()): ?>
        <form action="index.php?edit=<?php echo $row['group_id']?>">
            <button 
            type="submit"
            class="groupTab gEditShow glassPlate" 
            data-name="<?php echo $row['group_name'] ?>" 
            data-groupID="<?php echo $row['group_id'] ?>">
            <?php echo $row['group_name'] ?>
            <input name="groupID" type="hidden" value="<?php echo $row['group_id'] ?>">               
            </button>
        </form>    

    <?php 
    $groups[] = $row; 
    $encoded_data = json_encode($groups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('groups', $encoded_data);
    endwhile;}
    ?>

    </div>

    <a rel="modal:open"
    href="#groupBuilderForm">
    <button 
    type="button" 
    name="createGroup" 
    id="createGroupButton" 
    class="createButton gEditShow glassPlate">
    Create Group
    </button> 
    </a> 
</section><!-- //#groupList -->

<!-- when group pressed load group editor w populated fields-->
<?php if (isset($_GET['groupID'])): ?>
<?php 
$groupSelected = $_GET['groupID'];

$result = $mysqli->query("SELECT * FROM nebula_groups WHERE group_id = '$groupSelected'") or die($mysqli->error); ?>

<?php while($row = $result->fetch_assoc()): ?>

<form id="groupEditorForm" class="groupBuilder" action="process.php" method="POST">

    <label for="gName">Group Name:</label>
    <input type="hidden" display="none" name="gID" id="gID" value="<?php echo $row['group_id'];?>">
    <input type="text" id="gName" name="gName" placeholder="GroupName" value="<?php echo $row['group_name'] ?>">

    <div class="groupButtons"> 
        <a href="process.php?updateGroup=<?php echo $groupSelected;?>" id="updateGroup" >
        <button 
        type="submit" 
        class="updateGroup" 
        name="updateGroup">
        <i class='fas fa-save'></i> 
        <span>Save</span>
        </button>
        </a>


        <a href="process.php?deleteGroup=<?php echo $groupSelected;?>" id="deleteGroup" >
        <button 
        name="deleteGroup" 
        type="button" 
        class="deleteGroup">
        <i class="fas fa-trash"></i>
        <span>Delete</span>
        </button>
        </a>
    </div>

    <a rel="modal:open" href="#sectionBuilderForm" id="createSectionButton"> 
    <button type="button" data-groupID="<?php echo $groupSelected;?>"><i class="fas fa-lg fa-plus-hexagon"></i><span> Create Section</span>
    </button>
    </a>

</form>
<?php endwhile; ?>

<form id="sectionBuilderForm" class="sectionBuilder modal" action="process.php" method="POST">
    <div id="sectionBuilderContainer">      

        <div class="secBuilderNameID">
            <input id="sb_gID" type="hidden" name="group_id" value="<?php echo $groupSelected; ?>">
            <label for="sectionName" class="sectionNameLabel">Name:</label>
            <input 
            type="text" 
            name="sectionName" id="sectionName" class="sectionNameInput" value="" placeholder="Name your section">
        </div>

        <div id="secBuilderLength">
            <label for="length" class="sectionLengthLabel">Length (Pixels):</label>
            <input type="number" name="length" class="sectionLength" value="1" min="1" max="350">
            <!-- replace max w remaining pixels -->
        </div>

        <div id="secBuilderOffset">
            <label for="offset" class="sectionOffsetLabel">Offset:</label>
            <input type="number" name="offset" class="sectionOffset" value="0">
        </div>

        <div id="secBuilderDir">
            <label for="sectionDirection" class="selectionDirectionLabelection">Direction:</label>
            <select name="dir" class="sectionDirection" data-sectionID="sectionID">
                <option value="0">Forward</option>
                <option value="1">Reverse</option>
            </select>
        </div>

        <div id="secBuilderColor">
            <label for="color_order" class="lightTypeLabel">Color Order</label>
            <select name="color_order" class="lightType">
                <option value="rgb">RGB</option>
                <option value="grb">GRB</option>
                <option value="brg">BRG</option>
            </select>
        </div>

        <div id="secBuilderPattern">
            <label for="pattern" class="patternSelectLabel">Pattern</label>
            <select name="pattern" class="patternSelect">
                <option value="0">Rainbow</option>
                <option value="1">Strobe</option>
            </select>
        </div>

        <div class="sectionButtons">
            <button id="saveSection" class="saveSection" name="saveSection"><span>Save </span><i class='fas fa-xl fa-save'></i> </button>
            <button type="button" id="cancelSection" class="cancelSection"><span>Cancel </span><i class="fas fa-xl fa-trash"></i></button>
        </div>
    </div>
</form>

<?php endif; ?>

<section id="sectionList" class="sectionList">
<?php 
if (isset($_GET['groupID'])):
include 'views/mysqliStart.php';
$groupSelected = $_GET['groupID'];
$i = 0;
$result = $mysqli->query("SELECT * FROM group_sections WHERE group_id = '$groupSelected'") or die($mysqli->error);

if (mysqli_num_rows($result) == 0) {
    echo "You have no sections in this group yet.";
    } else { while($row = $result->fetch_assoc()): ?>
<?php
$colorOrder = $row['color_order'];
$direction = $row['direction'];
$pattern = $row['pattern'];
$gID = $row['group_id'];
$sID = $row['section_id'];
$i = $i+1;
?>

    <form class="groupSection" data-sectionID="<?php echo $row['section_id'] ?>" action="process.php" method="POST">
        <div class="secNameID">
            <label for="sectionName" class="sectionNameLabel"><i class="fa-regular fa-hexagon"></i> <?php echo $i ?></label>
            <input type="text" name="sectionName" id="sectionName" class="sectionNameInput" value="<?php echo $row['section_name'] ?>" placeholder="Section">
            <input type="hidden" name="group_id" value="<?php echo $gID ?>">
            <input type="hidden" name="section_id" value="<?php echo $sID ?>">
        </div>


        <div class="secLength">
            <label for="length" class="sectionLengthLabel">Length (Pixels):</label>
            <input type="number" name="length" class="sectionLength" value="<?php echo $row['length'] ?>" min="1" max="350">
            <!-- replace max w remaining pixels -->
        </div>

        <div class="secOffset">
            <label for="offset" class="sectionOffsetLabel">Offset:</label>
            <input type="number" name="offset" class="sectionOffset" value="<?php echo $row['offset'] ?>">
        </div>

        <div class="secDir">
            <label for="sectionDirection" class="selectionDirectionLabelection">Direction:</label>
            <select name="dir" class="sectionDirection" data-sectionID="sectionID">
                <option value="0" <?php if ($direction == 0){ echo 'selected'; }?> >Forward</option>
                <option value="1" <?php if ($direction == 1){ echo 'selected'; }?> >Reverse</option>
            </select>
        </div>

        <div class="secColor">
            <label for="colorOrder" class="lightTypeLabel">Color Order</label>
            <select name="color_order" class="lightType">
                <option value="rgb" <?=$colorOrder == 'rgb' ? ' selected="selected"' : '';?> >RGB</option>
                <option value="grb" <?=$colorOrder == 'grb' ? ' selected="selected"' : '';?> >GRB</option>
                <option value="brg" <?=$colorOrder == 'brg' ? ' selected="selected"' : '';?> >BRG</option>
            </select>
        </div>

        <div class="secPattern">
            <label for="pattern" class="patternSelectLabel">Pattern</label>
            <select name="pattern" class="patternSelect">
                <option value="0" <?=$pattern == 0 ? ' selected="selected"' : '';?> >Rainbow</option>
                <option value="1" <?=$pattern == 1 ? ' selected="selected"' : '';?> >Strobe</option>
            </select>
        </div>

        <div class="sectionButtons">
            <button type="submit" id="updateSection" class="updateSection" name="updateSection"><span>Update </span><i class='fas fa-xl fa-save'></i></button>

            <a href="process.php?deleteSection=<?php echo $sID.'&gID='.$gID;?>">
            <button type="button" id="deleteSection" class="deleteSection"><span>Delete</span> <i class="fas fa-xl fa-trash"></i></button>
            </a>
        </div>
    </form>                
<?php endwhile; } endif; ?>   
</section><!-- // sectionList -->

<form id="groupBuilderForm" class="groupBuilder modal" action="process.php" method="POST">
    <div class="groupBuilderContainer">
        <div class="groupBuilderNameID">
            <input type="hidden" display="none" id="gID" value="">
            <label for="gName" class="gNameLabel">Name:</label>
            <input type="text" id="gNameBuilder" name="gName" placeholder="GroupName" value="">
        </div>

        <div class="groupBuilderButtons">
            <button 
            type="submit" 
            id="saveGroup" 
            class="saveGroup" 
            name="saveGroup">
            Save <i class='fas fa-xl fa-save'></i> 
            </button>

            <button 
            type="button" 
            id="cancelGroup" 
            class="cancelGroup">
            Cancel <i class="fas fa-xl fa-trash"></i>
            </button>
        </div>
    </div>
</form> 
</section><!-- // #applet -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<script src="js/nebulize.js"></script>
</body>
</html>