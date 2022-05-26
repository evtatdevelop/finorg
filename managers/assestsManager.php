<?php
    require_once( 'modules/assetsData.php' );

    function assets( $props ) { return getAssrets( $props ); }
    function asset( $props ) { return getOneAsset( $props )[0]; }
