<?php
/**
 * The admin overview page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">
    <h2><?php echo SMW_SERVICE; ?> for Wordpress</h2>
    <p><?php echo SMW_SERVICE; ?> for WordPress is a plugin designed to help professional and amateur photographers integrate their public <strong>and private</strong> <?php echo SMW_SERVICE; ?> galleries into their WordPress websites and blogs.&nbsp; It brings most of the functionality of <?php echo SMW_SERVICE; ?> directly to your WordPress website, so you get the SEO benefits of WordPress with the functionality of <?php echo SMW_SERVICE; ?>. Below is a partial list of the features of <?php echo SMW_SERVICE; ?> for WordPress:</p>
    <ul class="smw-list">
        <li>Store all your photos at <?php echo SMW_SERVICE; ?>, but show them on your WordPress blog.</li>
        <li>Get the SEO benefit of WordPress with the file sharing benefit of <?php echo SMW_SERVICE; ?></li>
        <li>Allow your clients (or friends/family) to review your photos from your website without needing to go to <?php echo SMW_SERVICE; ?>.</li>
        <li>Create, Manage, &amp; Delete <?php echo SMW_SERVICE; ?> galleries from WordPress.</li>
        <li>Custom Post Type Templates (on a per page basis)</li>
        <li>Custom Gallery Templates (on a per gallery basis)</li>
        <li>PrettyPhoto built in with admin options available</li>
        <li>Automatic gallery list, so you never have to remember IDs</li>
        <li>Hide/Show Images</li>
        <li><strong><?php echo SMW_SERVICE; ?> password support.</strong> Have just one password that protects both your <?php echo SMW_SERVICE; ?> and WordPress galleries.</li>
    </ul>
    <h2>Documentation</h2>
    <p id="upgrade-text">There have been a lot of changes between 0.6.2 and 0.7, and this <strong>WILL BREAK</strong> your current installation. Please see our <a href="http://quantumdevonline.com/smugmug-for-wordpress/documentation" target="_blank">Documentation</a> or if you would like some personal attention <a href="http://quantumdevonline.com/smugmug-for-wordpress/questions-or-feedback" target="_blank">Contact Me</a>. To help with the template changes, I will be redoing any custom templates that worked with 0.6.2. Just go to this <a href="http://quantumdevonline.com/smugmug-for-wordpress/custom-template-updates" target="_blank">Form</a> and send me a zip file with the templates in it. Go here for an <a href="http://quantumdevonline.com/?p=351" target="_blank">Explanation</a> of why these changes were made.</p>
    <p>Please <a title="Documentation" target="_blank" href="http://quantumdevonline.com/smugmug-for-wordpress/documentation"><strong>Click Here</strong> </a>for our documentation.</p>
    
    <?php if(get_option('smw_access_token')): ?>
        <h2>Statistics</h2>
        <p>Below are some helpful statistics about your <?php echo SMW_SERVICE; ?> account.</p>
        <table class="form-table">
            <tbody>
                <tr scope="row">
                    <th><strong>Number of Galleries</strong></th>
                    <td><?php echo $this->args['gallery_count']; ?></td>
                </tr>
                <tr scope="row">
                    <th><strong>Number of Images</strong></th>
                    <td><?php echo $this->args['image_count']; ?></td>
                </tr>

                <tr scope="row">
                    <th><strong>Number of Passworded Albums</strong></th>
                    <td><?php echo $this->args['gallery_pass_count']; ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>