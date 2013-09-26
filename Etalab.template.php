<?php

/**
 * Display a gravatar from email
 */
function gravatar($email, $size=32) {
    $gravatar_link = 'http://www.gravatar.com/avatar/' . md5($email) . '?s='.$size;
    return '<img src="' . $gravatar_link . '" />';
}

/**
 * Get an URL from a title
 */
function wikiUrl($target) {
    $title = Title::newFromText($target);
    return $title->getLinkUrl();
}


/**
 * BaseTemplate class for ETALAB skin
 * @ingroup Skins
 */
class EtalabTemplate extends BaseTemplate {

    private function getTopics($lang='fr') {
        global $wgEtalabHomeUrl;
        return array(
            array('Culture et communication', 'culture', "$wgEtalabHomeUrl/$lang/group/culture-et-communication"),
            array('Développement durable', 'wind', wikiUrl('Le Développement Durable')),
            array('Éducation et recherche', 'education', "$wgEtalabHomeUrl/$lang/group/education-et-recherche"),
            array('État et collectivités', 'france', "$wgEtalabHomeUrl/$lang/group/etat-et-collectivites"),
            array('Europe', 'europe', "$wgEtalabHomeUrl/$lang/group/culture-et-communication"),
            array('Justice', 'justice', "$wgEtalabHomeUrl/$lang/group/justice"),
            array('Monde', 'world', "$wgEtalabHomeUrl/$lang/group/monde"),
            array('Santé et solidarité', 'heart', "$wgEtalabHomeUrl/$lang/group/sante-et-solidarite"),
            array('Sécurité et défense', 'shield', "$wgEtalabHomeUrl/$lang/group/securite-et-defense"),
            array('Société', 'people', "$wgEtalabHomeUrl/$lang/group/societe"),
            array('Travail, économie, emploi', 'case', "$wgEtalabHomeUrl/$lang/group/travail-economie-emploi"),
        );
    }

    /**
     * Outputs the entire contents of the page
     */
    public function execute() {
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();

        $this->html( 'headelement' );
        $this->render_top_nav();
        ?>

        <div class="full-container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                <?php $this->render_sidebar(); ?>
                </div>

                <div class="col-sm-9 col-md-9">
                    <?php if($this->data['sitenotice']) { ?>
                        <div id="siteNotice" class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php $this->html('sitenotice') ?>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs">
                                <?php foreach ( $this->data['content_navigation']['namespaces'] as $key => $tab ) { ?>
                                        <?php echo $this->makeListItem( $key, $tab ); ?>

                                <?php } ?>
                                <?php foreach ( $this->data['content_navigation']['views'] as $key => $tab ) {
                                    if ( isset( $tab['class'] ) ) {
                                        $tab['class'] .= ' pull-right';
                                    } else {
                                        $tab['class'] = 'pull-right';
                                    }
                                    echo $this->makeListItem( $key, $tab, ['class'=> 'pull-right'] );

                                } ?>
                            </ul>
                        </div>
                    </div>

                    <div class="page-header">
                        <h1 lang="fr">
                            <?php $this->html( 'title' ) ?>
                            <small><?php $this->html( 'tagline' ) ?></small>
                        </h1>
                    </div>
                    <?php $this->html( 'subtitle' ) ?>
                    <div class="row">
                        <div id="content" class="mw-body col-md-12" role="main">
                            <?php $this->html( 'bodytext' ) ?>
                        </div>
                    </div>
                        <!-- /bodyContent -->
                </div>
            </div>

            <hr/>
            <footer>
                <p class="pull-right"><a href="#"><?php $this->msg( 'back-to-top' ); ?></a></p>
                <p>
                    &copy; 2013 ETALAB, Inc. &middot;
                    <a href="#"><?php $this->msg( 'privacy' ); ?></a> &middot;
                    <a href="#"><?php $this->msg( 'terms' ); ?></a>
                </p>
            </footer>

        </div>


        <!--[if lt IE 9]>
            <script src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('js/etalab-mediawiki-legacy.min.js') ) ?>"></script>
        <![endif]-->
        <!--[if gte IE 9]><!-->
            <script src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('js/etalab-mediawiki.min.js') ) ?>"></script>
        <!--<![endif]-->

        <?php $this->printTrail(); ?>
        </body>
        </html><?php

        wfRestoreWarnings();
    }

    private function render_top_nav() {
        global $wgEtalabHomeUrl;
        ?>
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <header class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-collapse, .subnav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $wgEtalabHomeUrl; ?>">Etalab2.fr</a>
            </header>

            <div class="collapse navbar-collapse">
                <p class="navbar-text"><?php $this->msg( 'etalab-site-desc' ); ?></p>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <button class="btn btn-dark navbar-btn dropdown-toggle" data-toggle="dropdown">
                            <?php if ($this->data['loggedin']) {
                                echo gravatar($this->data['usermail'], 20) . ' ' . $this->data['username'];
                            } else {
                                ?><?php $this->msg( 'sign-in-register' ); ?><?php
                            }
                            ?>
                            <b class="caret"></b>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if ($this->data['loggedin']) { ?>

                            <li>
                                <a href="<?php echo $this->data['personal_urls']['userpage']['href'] ?>" title="Profil">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <?php $this->msg( 'profile' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->data['personal_urls']['preferences']['href'] ?>"
                                    title="<?php echo $this->data['personal_urls']['preferences']['text'] ?>">
                                    <span class="glyphicon glyphicon-wrench"></span>
                                    <?php echo $this->data['personal_urls']['preferences']['text'] ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->data['personal_urls']['mytalk']['href'] ?>"
                                    title="<?php echo $this->data['personal_urls']['mytalk']['text'] ?>">
                                    <span class="glyphicon glyphicon-comment"></span>
                                    <?php echo $this->data['personal_urls']['mytalk']['text'] ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->data['personal_urls']['watchlist']['href'] ?>" title="Liste de suivi">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                    <?php echo $this->data['personal_urls']['watchlist']['text'] ?>
                                </a>
                            </li>
                            <li role="presentation" class="divider"></li>
                            <li>
                                <a href="<?php echo $this->data['personal_urls']['logout']['href'] ?>"
                                    title="<?php echo $this->data['personal_urls']['logout']['text'] ?>">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    <?php echo $this->data['personal_urls']['logout']['text'] ?>
                                </a>
                            </li>

                            <?php } else { ?>

                                <?php if ( $this->data['personal_urls']['login'] ) { ?>
                                <!-- login -->
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['login']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['login']['text'] ?>">
                                        <span class="glyphicon glyphicon-user"></span>
                                        <?php echo $this->data['personal_urls']['login']['text'] ?>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if ( $this->data['personal_urls']['anonlogin'] ) { ?>
                                <!-- login -->
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['anonlogin']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['anonlogin']['text'] ?>">
                                        <span class="glyphicon glyphicon-user"></span>
                                        <?php echo $this->data['personal_urls']['anonlogin']['text'] ?>
                                    </a>
                                </li>
                                <?php } ?>
                                <!-- register -->
                                <?php if ( $this->data['personal_urls']['createaccount'] ) { ?>
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['createaccount']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['createaccount']['text'] ?>">
                                        <span class="glyphicon glyphicon-edit"></span>
                                        <?php echo $this->data['personal_urls']['createaccount']['text'] ?>
                                    </a>
                                </li>
                                <?php } ?>

                            <?php } ?>


                        </ul>
                    </li>
                    <li class="dropdown">
                        <button class="btn btn-dark navbar-btn dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/'.strtoupper($this->data['userlang']).'.png') ) ?>" />
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">
                                    <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/FR.png') ) ?>" />
                                    Français
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/US.png') ) ?>" />
                                    English
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <nav class="navbar navbar-static-top navbar-subnav" role="navigation">
            <div class="navbar-left col-sm-4 col-md-3 col-xs-12 collapse subnav-collapse">
                <a class="btn btn-primary navbar-btn btn-block"
                        title="<?php $this->msg('publish-dataset') ?>"
                        href="<?php echo $wgEtalabHomeUrl . '/' . $this->data['userlang'] .'/dataset/new'; ?>">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                    <?php $this->msg('publish-dataset') ?>
                </a>
            </div>
            <div class="col-sm-8 col-md-9 col-xs-12 navbar-left">
                <form class="navbar-form form-inline" role="search"
                    action="<?php echo $wgEtalabHomeUrl . '/' . $this->data['userlang'] .'/search'; ?>">
                    <div class="input-group col-sm-8 col-xs-12">
                        <div class="input-group-btn">
                            <button class="btn" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <input id="search-input" name="q" type="search" class="form-control" autocomplete="off"
                                placeholder="<?php $this->msg('search') ?>">
                    </div>
                    <div class="collapse subnav-collapse col-sm-4 col-xs-12">
                        <div id="where-group" class="input-group">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-globe"></span>
                            </span>
                            <input id="where-input" type="search" class="form-control" autocomplete="off"
                                    placeholder="<?php $this->msg('where') ?>">
                            <input id="ext_territory" name="ext_territory" type="hidden" />
                        </div>
                    </div>
                </form>
            </div>
        </nav>
        <?php
    }

    private function render_sidebar() { ?>
        <nav id="sidebar" class="panel">
        <?php foreach ( $this->data['sidebar'] as $name => $content ) {
            if ( !$content ) {
                continue;
            }
            $msgObj = wfMessage( $name );
            $name = htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $name );
        ?>
            <!-- <?php echo htmlspecialchars( $name ); ?> -->
            <div class="panel-heading">
                <a href data-toggle="collapse" data-target="#panel-<?php echo $name;?>">
                    <?php echo htmlspecialchars( $name ); ?>
                </a>
            </div>
            <div id="panel-<?php echo $name;?>" class="panel-body collapse in">
                <ul class="list-unstyled">
                <?php
                    foreach( $content as $key => $val ) {
                        $navClasses = '';

                        if (array_key_exists('view', $this->data['content_navigation']['views']) && $this->data['content_navigation']['views']['view']['href'] == $val['href']) {
                            $navClasses = 'active';
                        }
                ?>

                    <li class="<?php echo $navClasses ?>"><?php echo $this->makeLink($key, $val); ?></li>
                <?php
                    }
            }?>
                </ul>
            </div>

            <!-- Toolbox -->
            <div class="panel-heading">
                <a href data-toggle="collapse" data-target="#panel-toolbox">
                    <?php $this->msg('toolbox') ?>
                </a>
            </div>
            <div id="panel-toolbox" class="panel-body collapse in">
                <ul class="list-unstyled">
                <?php
                        foreach ( $this->getToolbox() as $key => $tbitem ) { ?>
                                <?php echo $this->makeListItem( $key, $tbitem ); ?>

                <?php
                        }
                        wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) ); ?>
                </ul>
            </div>

            <div class="list-group">
                <?php foreach ($this->getTopics() as $topic) {
                    $name = $topic[0];
                    $icon = $topic[1];
                    $url = $topic[2];
                ?>
                    <a class="list-group-item" href="<?php echo $url; ?>" title="<?php echo $name; ?>">
                        <span class="icon icon-<?php echo $icon; ?>"></span>
                        <?php echo $name; ?>
                    </a>
                <?php } ?>
            </div>
        </nav>
        <?php
    }


}
