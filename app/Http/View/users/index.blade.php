<?php
/**
 * @var array $userData
 * @var array $projectsData
 * @var array $packsData
 * @var array $branches
 * @var array $data
 * @var $view \Admin\View
 */

use Service\Breadcrumbs\Breadcrumb;

$view->addBreadcrumb(new Breadcrumb('Profile', 'fa-solid fa-user'));
?>

@extends('./layout.blade.php')

@section('content')
<style>
    .menu-elipsis {
        white-space: nowrap;
        display: block;
        width: 100%;
        overflow-x: hidden;
        text-overflow: ellipsis;
    }
    .ssh-key-ok {
        font-size: 1.6em;
        color: #0a0;
        vertical-align: bottom;
    }
    .ssh-key-missed {
        font-size: 1.6em;
        color: #a00;
        vertical-align: bottom;
    }
</style>
<div class="content">

    <div class="pure-g">
        <div class="pure-u-1-1">
            <h2>SSH Key</h2>
            <div>
                @if ($sshKeyUploaded)
                    <i class="fa-solid fa-check ssh-key-ok"></i> <span>Already uploaded</span>
                @else
                    <i class="fa-solid fa-xmark ssh-key-missed"></i> <span>Not uploaded</span>
                @endif
            </div>
        </div>
        <div class="pure-u-1-1">
            <h2>{{ __('actions') }}</h2>
            <a class="pure-button" href="/user/addkey">
                {{ $sshKeyUploaded ? __('replace_ssh_key') : __('add_ssh_key') }}
            </a>
        </div>
    </div>

</div>
@endsection