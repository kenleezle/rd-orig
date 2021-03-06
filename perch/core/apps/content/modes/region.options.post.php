<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php printf(PerchLang::get("Set options for the region here, or %s return to editing your content.%s"), '<a href="'.PERCH_LOGINPATH.'/core/apps/content/edit/?id='.PerchUtil::html($id).'">', '</a>'); ?>
    </p>
    
    <p><?php echo PerchLang::get('You can set options for this region, including whether to allow one or multiple items, and the sort order.'); ?></p>

    <h4><span><?php echo PerchLang::get('Search result URL'); ?></span></h4>
    <p>
        <?php echo PerchLang::get('It\'s sometimes useful to use a different URL in search results.'); ?>
    </p>
    <p>
        <?php printf(PerchLang::get('If you need this, enter the root-relative URL using %sbraces%s around any dynamic fields. e.g.'), '{', '}'); ?>
    </p>
    <p>
        <code><?php  printf(PerchLang::get('/news-article.php?s=%sslug%s'), '{','}'); ?></code>
    </p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


	    <h1><?php echo PerchLang::get('Editing Region Options'); ?></h1>
	

		<ul class="smartbar">
            <li>
				<span class="set">
				<a class="sub" href="<?php 
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>">Regions</a> 
				<span class="sep icon"></span> 
				<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($id);?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
				</span>
			</li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li class="selected"><a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($id).'">' . PerchLang::get('Region Options') . '</a></li>';
		        }

                if ($Region->regionMultiple()) {
                    echo '<li class="fin">';
                    echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/region/?id='.PerchUtil::html($Region->id()).'" class="icon reorder">Reorder</a>';
                    echo '</li>';
                }
			?>
        </ul>
		




    <?php echo $Alert->output(); ?>

    
    
        
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">
        
        <h2><?php echo PerchLang::get('Editing'); ?></h2>
        
        <div class="field">
            <?php echo $Form->label('contentShared', 'Share across all pages'); ?>
            <?php
                if ($Region->regionPage() == '*') {
                    $tmp = array('contentShared'=>'1');
                }else{
                    $tmp = array('contentShared'=>'0');
                }
                echo $Form->checkbox('contentShared', '1', $Form->get($tmp, 'contentShared', 0)); ?>
        </div>
        
        <div class="field <?php echo ($Region->regionMultiple() ? '' : 'last'); ?>">
            <?php echo $Form->label('regionMultiple', 'Allow multiple items'); ?>
            <?php echo $Form->checkbox('regionMultiple', '1', $Form->get(array('regionMultiple'=>$Region->regionMultiple()), 'regionMultiple', 0)); ?>
        </div>

    <?php if ($Region->regionMultiple()) { ?>
        
        <div class="field">
            <?php echo $Form->label('edit_mode', 'Edit all on one page'); ?>
            <?php echo $Form->checkbox('edit_mode', 'singlepage', $Form->get($options, 'edit_mode', 'singlepage')); ?>
        </div>
    
    
        <div class="field last">
            <?php echo $Form->label('addToTop', 'New items are'); ?>
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Added to the top'), 'value'=>1);
                $opts[] = array('label'=>PerchLang::get('Added to the bottom'), 'value'=>0);
                echo $Form->select('addToTop', $opts, $Form->get($options, 'addToTop', 0));
            ?>
        </div>
        
        <h2><?php echo PerchLang::get('Display'); ?></h2>

        <div class="field">
            <?php echo $Form->label('sortField', 'Sort by'); ?>
            <?php
                $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');
                $tags   = $Template->find_all_tags('content');
                $seen_tags = array();
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Default order'), 'value'=>'');
                if (PerchUtil::count($tags)) {
                    foreach($tags as $Tag) {
                        if (!in_array($Tag->id(), $seen_tags) && $Tag->label() && $Tag->id()) {
                            $opts[] = array('label'=>$Tag->label(), 'value'=>$Tag->id());
                            $seen_tags[] = $Tag->id();
                        }
                        
                    }
                }
                echo $Form->select('sortField', $opts, $Form->get($options, 'sortField'));
            
            ?>
        </div>
        
        <div class="field">
                <?php echo $Form->label('sortOrder', 'Sort order'); ?>
                <?php
                    $opts = array();
                    $opts[] = array('label'=>PerchLang::get('Ascending (A-Z, oldest to newest)'), 'value'=>'ASC');
                    $opts[] = array('label'=>PerchLang::get('Descending (Z-A, newest to oldest)'), 'value'=>'DESC');
                    echo $Form->select('sortOrder', $opts, $Form->get($options, 'sortOrder'));
                ?>
        </div>


        <div class="field last">
                <?php echo $Form->label('limit', 'Number of items to display'); ?>
                <?php
                    echo $Form->text('limit', $Form->get($options, 'limit'), 's');
                    echo $Form->hint(PerchLang::get('Leave blank to display all items'));
                ?>
        </div>
    <?php } ?>
    
    
        <h2><?php echo PerchLang::get('Search'); ?></h2>

        <div class="field">
            <?php echo $Form->label('regionSearchable', 'Include in search results'); ?>
            <?php
                $tmp = array('regionSearchable'=>$Region->regionSearchable());
                echo $Form->checkbox('regionSearchable', '1', $Form->get($tmp, 'regionSearchable', 1)); ?>
        </div>

        <div class="field last">
            <?php echo $Form->label('searchURL', 'URL for search results'); ?>
            <?php echo $Form->text('searchURL', $Form->get($options, 'searchURL', '')); ?>
        </div>

        
        <h2><?php echo PerchLang::get('Permissions'); ?></h2>

        <div class="field last">
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Everyone'), 'value'=>'*', 'class'=>'single');
                
                $vals = explode(',', $Region->regionEditRoles());

                if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $tmp = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());

                        if ($Role->roleMasterAdmin()) {
                            $tmp['disabled'] = true;
                            $vals[] = $Role->id();
                        }

                        $opts[] = $tmp;
                    }
                }
                
                
                
                echo $Form->checkbox_set('edit_roles', 'May be edited by', $opts, $vals, $class='', $limit=false);
            
            
            ?>
        </div>

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>