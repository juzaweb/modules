import {Head, usePage} from "@inertiajs/react";
import React from "react";
import MenuTop from "./components/menu-top";
import MenuLeft from "./components/menu-left";
import {upload_url, url} from "../helpers/functions";

export default function Admin({children}: { children: React.ReactNode }) {
    const {title} = usePage<{ title?: string }>().props;

    return (
        <>
            <Head>
                <title>{(title || '') + ' - Juzaweb CMS'}</title>
            </Head>

            <div className="juzaweb__layout juzaweb__layout--hasSider">
                <div className="juzaweb__menuLeft">
                    <div className="juzaweb__menuLeft__mobileTrigger"><span></span></div>

                    <div className="juzaweb__menuLeft__outer">
                        <div className="juzaweb__menuLeft__logo__container">
                            <a href="/admin-cp">
                                <div className="juzaweb__menuLeft__logo">
                                    <img src={ url('/jw-styles/juzaweb/images/logo.svg') } className="mr-1"
                                         alt="Juzaweb"/>
                                    <div className="juzaweb__menuLeft__logo__name">JuzaWeb</div>
                                    <div className="juzaweb__menuLeft__logo__descr">Cms</div>
                                </div>
                            </a>
                        </div>

                        <div className="juzaweb__menuLeft__scroll jw__customScroll">
                            <MenuLeft />
                        </div>
                    </div>
                </div>

                <div className="juzaweb__menuLeft__backdrop"></div>

                <div className="juzaweb__layout">
                    <div className="juzaweb__layout__header">
                        <MenuTop/>
                    </div>

                    <div className="juzaweb__layout__content">
                        {/*@if(!request()->is(config('juzaweb.admin_prefix')))
                    {{
                        jw_breadcrumb(
                        'admin',
                        [
                            [
                                'title' => $page['props']['title'] ?? '',
                        ]
                        ]
                        )
                    }}
                    @else
                    <div className="mb-3"></div>
                    @endif*/}

                        <h4 className="font-weight-bold ml-3 text-capitalize">{title || ''}</h4>

                        <div className="juzaweb__utils__content">
                            {children}
                        </div>

                    </div>

                    <div className="juzaweb__layout__footer">
                        <div className="juzaweb__footer">
                            <div className="juzaweb__footer__inner">
                                <a href="https://juzaweb.com" target="_blank" rel="noopener noreferrer"
                                   className="juzaweb__footer__logo">
                                    Juzaweb - Build website professional
                                    <span></span>
                                </a>
                                <br/>
                                <p className="mb-0">
                                    Copyright © 2020 {title} - Provided by Juzaweb
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
