import {__, admin_url, url} from "../../helpers/functions";
import {usePage} from "@inertiajs/react";

export default function MenuTop() {
    const {user, langs, currentLang, totalNotifications} = usePage<{ user?: any, langs?: Array<{ code: string, name: string }>, currentLang: string, totalNotifications: number }>().props;

    return <div className="juzaweb__topbar">
        <div className="mr-3">
            <a href={url('/')} className="mr-2" target="_blank" title={__('cms::app.view_site')}>
                <i className="dropdown-toggle-icon fa fa-home"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   data-original-title="Visit website"></i> {__('cms::app.view_site')}
            </a>
        </div>

        <div className="mr-3">
            <div className="dropdown mr-4 d-none d-sm-block">
                <a href="#" className="dropdown-toggle text-nowrap" data-toggle="dropdown">
                    <i className="fa fa-plus"></i>
                    <span className="dropdown-toggle-text"> {__('cms::app.new')}</span>
                </a>

                <div className="dropdown-menu" role="menu">
                    <a className="dropdown-item"
                       href={admin_url('post-type/posts/create')}>{__('cms::app.post')}</a>

                    <a className="dropdown-item"
                       href={admin_url('post-type/pages/create')}>{__('cms::app.page')}</a>

                    <a className="dropdown-item" href={admin_url('users/create')}>{__('cms::app.user')}</a>
                </div>
            </div>
        </div>

        <div className="mr-auto"></div>

        <div className="dropdown mr-4 d-none d-sm-block">
            <a href="#"
               className="dropdown-toggle text-nowrap"
               data-toggle="dropdown"
               data-offset="5,15"
               aria-expanded="false"
            >
                <span className="dropdown-toggle-text text-uppercase">{currentLang}</span>
            </a>

            <div className="dropdown-menu dropdown-menu-right" role="menu">
                {langs?.map((lang) => {
                    if (lang.code == currentLang) {
                        return null;
                    }

                    return (
                        <a key={lang.code} className="dropdown-item " href={url('/?hl=' + lang.code)}>
                            <span className="text-uppercase font-size-12 mr-1">{lang.code}</span>
                            {lang.name}
                        </a>
                    )
                })}
            </div>
        </div>

        <div className="juzaweb__topbar__notify dropdown mr-4 d-none d-sm-block">
            <a href="#" className="dropdown-toggle text-nowrap" data-toggle="dropdown"
               aria-expanded="false" data-offset="0,15">
                <i className="dropdown-toggle-icon fa fa-bell-o"></i> <span>{totalNotifications}</span>
            </a>

            <div className="juzaweb__topbar__actionsDropdownMenu dropdown-menu dropdown-menu-right" role="menu">
                <div style={{width: '350px'}}>
                    <div className="card-body">
                        <div className="tab-content">
                            <div className="jw__l1">
                                <div
                                    className="text-uppercase mb-2 text-gray-6 mb-2 font-weight-bold">{__('cms::app.notifications')} ({totalNotifications})
                                </div>
                                <hr/>
                                <ul className="list-unstyled">
                                    {/*@if($items->isEmpty())
                                        <p>{{ __('cms::app.no_notifications') }}</p>
                                        @else
                                        @foreach($items as $notify)
                                        <li className="jw__l8__item">
                                            <a href="{{ route('admin.profile.notification', [$notify->id]) }}" className="jw__l8__itemLink" data-turbolinks="false">
                                                <div className="jw__l8__itemPic bg-success">
                                                    @if(empty($notify->data['image']))
                                                    <i className="fa fa-envelope-square"></i>
                                                    @else
                                                    <img src="{{ upload_url($notify->data['image']) }}" alt="">
                                                        @endif
                                                </div>
                                                <div>
                                                    <div className="text-blue">{{ $notify->data['subject'] ?? '' }}</div>
                                                    <div className="text-muted">{{ $notify->created_at?->diffForHumans() }}</div>
                                                </div>
                                            </a>
                                        </li>
                                        @endforeach
                                        @endif*/}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div className="dropdown">
            <a href="#" className="dropdown-toggle text-nowrap" data-toggle="dropdown" aria-expanded="false"
               data-offset="5,15">
                <img className="dropdown-toggle-avatar" src={ user?.avatar } alt="User avatar" width="30"
                     height="30"/>
            </a>

            <div className="dropdown-menu dropdown-menu-right" role="menu">
                <a className="dropdown-item" href={admin_url('profile')}>
                    <i className="dropdown-icon fa fa-user"></i>
                    {__('cms::app.profile')}
                </a>

                <div className="dropdown-divider"></div>
                <a href="#" data-turbolinks="false" className="dropdown-item auth-logout">
                    <i className="dropdown-icon fa fa-sign-out"></i> {__('cms::app.logout')}
                </a>
            </div>
        </div>
    </div>
}