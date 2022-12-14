$(function(){ //doc ready
	$groupBuilder = $('#groupBuilderForm');
	$sectionBuilder = $('#sectionBuilderForm'); 
	$sB_ID = $('#sb_gID');
	$grpCButton = $('#createGroupButton');
	$secCButton = $('#createSectionButton');
/*
//show empty group editor when create group button clicked
$($grpCButton).on('click', function(){
	$($groupBuilder).show();
	$($sectionBuilder).hide();
});

//show empty section editor when create group button clicked
$($secCButton).on('click', function(groupID){
	var $groupID = $(this).attr('data-groupID');
	$($sectionBuilder).show();
	$($groupBuilder).hide();
	$($sB_ID).val($groupID);
});
*/
$('#cancelGroup').on('click', function(){
	// clear groupBuilderForm and close
	$($groupBuilder).trigger("reset").slideUp();
});

$('#cancelSection').on('click', function(){
	// clear sectionBuilderForm and close
	$($sectionBuilder).trigger("reset").slideUp();
});

$('#notifPop').delay(2000).fadeOut();

$('#gName').on('focus', function(){
	$('#updateGroup').slideDown();
});

$('#updateGroup').on('click', function(){
	$('#updateGroup').slideUp();
});

$('#createSectionButton').on('click', function(){
	$sectionNumber = $("#sectionList > form").length+1;
	$('#sectionName').val("Section_" + $sectionNumber);
	console.log('ya3');
});


});//doc ready