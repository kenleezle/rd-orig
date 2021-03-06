<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
	<p><?php echo PerchLang::get('Check the box for each privilege this role should have.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
		if (is_object($Role)) {
			echo PerchLang::get('Editing %s Role', $Role->roleTitle());
		}else{
			echo PerchLang::get('Adding a New Role');
		}
		 ?></h1>
	

    
    <?php echo $Alert->output(); ?>

    
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned">
		
		<h2><?php echo PerchLang::get('Role details'); ?></h2>
		
        <div class="field last <?php echo $Form->error('roleTitle', false);?>">
            <?php echo $Form->label('roleTitle', 'Title'); ?>
            <?php echo $Form->text('roleTitle', $Form->get($details, 'roleTitle'), ''); ?>
        </div>
        
        <h2><?php echo PerchLang::get('Privileges'); ?></h2>

        <?php
            if (PerchUtil::count($privs)) {
                
                $previous = false;
                
                foreach($privs as $Priv) {
                    if ($Priv->app() != $previous && $previous !==false) {
                        
                        if ($previous !== false) {
                            echo '<div class="field">';
                            echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs);
                            echo '</div>';
                        }
                        
                        $opts = array();
                        
                    }

                    if (is_object($Role)) {
                        $disabled = $Role->roleMasterAdmin();
                    }else{
                        $disabled = false;
                    }
                    
                    $opts[] = array('label'=>$Priv->privTitle(), 'value'=>$Priv->id(), 'disabled'=>$disabled);
                    
                    $previous = $Priv->app();
                }
                
                if (PerchUtil::count($opts)) {
                    echo '<div class="field">';
                    echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs);
                    echo '</div>';
                }
                
                
            }
            
            
        
        
        ?>


		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Save changes', 'button');
				echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users">' . PerchLang::get('Cancel'). '</a>'; 
				
			?>
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>