<?php
include_once('../viewclasses/WelcomepageView.php');
include_once('../objects/Job.php');
session_start();
$errorId = $_REQUEST['ERROR_ID'];

$welcomepageView = new WelcomepageView();
$jobArray = $welcomepageView->initialize();
$job = new Job();
if($errorId == 1) {
    echo "<SCRIPT type='text/javascript'>";
    echo "alert('No Job Found')";
    echo "</script>";
}
?>

<html>
<body>
<script type="text/javascript">
    function validateCompany()
    {
        if (JOBSEARCH.COMPANY_NAME.value == "")
        {
            // If null display and alert box
            alert("Please fill in the text field.");
            // Place the cursor on the field for revision
            JOBSEARCH.COMPANY_NAME.focus();
            // return false to stop further processing
            return (false);
         }
        // If text_name is not null continue processing
        return (true);
    }
</script>

<h1>Congratulations ! You have successfully logged into Smart Recruit platform </h1>
<br>
<h2>You have the following active jobs in database</h2>
<table border=1>
    <tr>
        <th>Job Id</th>
        <th>Job Title</th>
        <th>Location</th>
        <th>Posted On</th>
        <th>Expiring On</th>
        <th>Actions</th>
    </tr>
    <?php
        if(isset($jobArray)) {
            foreach($jobArray as $job) {
                $url = 'controller.php?PAGE_ID=CREATE_INTERVIEW&jobId='.$job->getJobId().'&jobTitle='.$job->getTitle();
    ?>
        <tr>
            <td><?php echo $job->getJobId(); ?></td>
            <td><?php echo $job->getTitle(); ?></td>
            <td><?php echo $job->getLocation(); ?></td>
            <td><?php echo $job->getPostingDate(); ?></td>
            <td><?php echo $job->getExpirationDate(); ?></td>
            <td><a href="<?php echo $url; ?>"><input type='Button' value='Create Interview' /></a> &nbsp;&nbsp;&nbsp;
                <input type='button' value="Follow Up Interviews"> &nbsp;&nbsp;&nbsp;
                <input type='button' value="DeActivate"> &nbsp;&nbsp;&nbsp;
            </td>
        </tr>

    <?php
            }
        }
    ?>
</table>
<br><br>
<h3>You can search Jobs which you have created and create interviews for the same. </h3>

Please import jobs using company name which you have used to create jobs in LinkedIn<br><br>
<form name="JOBSEARCH" method="POST" action="controller.php" onsubmit="return validateCompany()">
    <b>Company Name : </b><input type='TEXT' id="COMPANY_NAME" name="COMPANY_NAME" size="30"/><br><br>
    <input type="hidden" name="PAGE_ID" value="JOB_DISPLAY" />
    <input type="submit" value="IMPORT" />
</form>
<form name="Quotient" method="POST" action="controller.php"">
    <input type="hidden" name="PAGE_ID" value="QUOTIENT_DISPLAY" />
    <input type="submit" value="My Quotient!" />
</form>

</body>
</html>

