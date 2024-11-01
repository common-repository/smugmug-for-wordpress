<?php
/**
 * The create authorization block
 * 
 * This is the block that shows the create authorization
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<h3>Authorize <?php echo SMW_SERVICE; ?> for Wordpress</h3>

<p><a href='<?php echo $this->service->getAuthorizeLink(); ?>&iframe=true&width=900' class="button-primary" rel='prettyPhoto'>Click <strong>HERE</strong></a> to Authorize This Plugin to Access Your <?php echo SMW_SERVICE; ?> Account.</p>
<p>A new window/tab will open asking you to login to <?php echo SMW_SERVICE; ?> (if not already logged in).  Once you've logged it, <?php echo SMW_SERVICE; ?> will redirect you to a page asking you to approve the access (it's read only) to your public photos.  Approve the request and come back to this page and click REFRESH below.</p>
<p><a href='<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'] ?>' class="button-primary"><strong>REFRESH PAGE</strong></a></p>