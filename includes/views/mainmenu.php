<style type="text/css">
  .settingleftcollumn{
    border: 1px solid rgb(217,213,213);
    border-radius: 1%;
    padding: 1%;
    margin-top: 1%;
    background-color: white;
    float:left;
}
</style>

    <div class="settingleftcollumn">           
    <h2><span class="dashicons dashicons-admin-generic"></span> Setting WP Security Master</h2>
      <form method="post" action="">
          <!-- Guardian with nonce -->
          <input type="hidden" name="wps_nonce" value="<?= $nonce?>">
          <table class="form-table" style="border: 1px solid none">
              <tr>
              	<td colspan="2">
                    <p><strong>Current configuration: </strong></p><br>
                    <?= $timetotrigger ?><?php if(isset($getseconds)){echo " <strong><span id='time' style = 'color: green'></span></strong>";}?><br><br>
                    
                    Setting up your passcode, at least 8 characters. By default, the admin dashboard is disabled after 1 hour.<br><br>

                    <?php if($state == true){
                     echo '<span class="dashicons dashicons-arrow-right"> </span> <input type="checkbox" checked="checked" name="wps_check"> Uncheck if you want to change the passcode. Otherwise the previous passcode is being used. <br><br>

                     <span class="dashicons  dashicons-arrow-right"> </span> A <span style ="color: red"><strong>new</strong></span> timespan will be set when the configuration is made.<br><br>';
                    }
                    ?>

                    <span style="color: red"><?php if(isset($error[0])){echo $error[0];}?><br><br></span>
                    
                    Passcode: <input type="password" name="wps_password">
                    Re-passcode: <input type="password" name="wps_password_confirm">                
                 </td>
              </tr>
                     <tr style="border: 1px solid none">
                <td colspan="2">
                    <strong>Automatically disable</strong> the admin dashboard after: 
                    <input type="text" name="wp_security_master_time_value" size="2" value="1" />
                    <select name="wp_security_master_time_unit">
                          <option value="minutes">Minutes</option>
                          <option value="hours" selected>Hours</option>
                          <option value="days">Days</option>
                          <option value="month">Months</option>
                          <option value="year">Years</option>
                    </select> 
                 </td>
              </tr>              
          </table>
        <br>
        <input type="submit" name="submit_savechange" id="submit" class="button button-primary" value="🔐 Save change"  />
        <input type="submit" name="submit_cancel" id="submit_cancel" class="button button-primary" value="Cancel"  />
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QU42NUQQ4YADG&source=url" target="_blank">
        <input type = "button" class="button button-primary" value= "❤️ Donate"/>  
        </a>        
      </form>
    </div>

