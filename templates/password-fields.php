<?php if($this->hint): ?>
    <p><strong>Password Hint:</strong> <?php echo $this->hint; ?></p>
<?php endif; ?>
<form action="" method="post" accept-charset="utf-8">
    <table style="wide-fat">
        <tbody>
            <tr>
                <td>Password:</td>
                <td><input name="password" type="password" value="" /></td>
            </tr>
        </tbody>
    </table>
    <p><input type="submit" class="button-primary" value="Continue &rarr;"/></p>
</form>