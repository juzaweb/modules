import {Head, Link, usePage} from "@inertiajs/react";
import React from "react";
import MenuTop from "./components/menu-top";
import MenuLeft from "./components/menu-left";
import {__, admin_url, url} from "../helpers/functions";
import {BaseAdminPageProps} from "../types/config";

export default function Admin({children}: { children: React.ReactNode }) {
    const {title, config, adminPrefix, adminUrl, currentPath, breadcrumbItems} = usePage<BaseAdminPageProps>().props;

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
                            <Link href={adminUrl}>
                                <div className="juzaweb__menuLeft__logo">
                                    <img src={url('/jw-styles/juzaweb/images/logo.svg')} className="mr-1"
                                         alt="Juzaweb"/>
                                    <div className="juzaweb__menuLeft__logo__name">JuzaWeb</div>
                                    <div className="juzaweb__menuLeft__logo__descr">Cms</div>
                                </div>
                            </Link>
                        </div>

                        <div className="juzaweb__menuLeft__scroll jw__customScroll">
                            <MenuLeft/>
                        </div>
                    </div>
                </div>

                <div className="juzaweb__menuLeft__backdrop"></div>

                <div className="juzaweb__layout">
                    <div className="juzaweb__layout__header">
                        <MenuTop/>
                    </div>

                    <div className="juzaweb__layout__content">
                        {currentPath !== adminPrefix ? (
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb">
                                    <li className="breadcrumb-item">
                                        <Link href={admin_url()}>{__('cms::app.dashboard')}</Link>
                                    </li>

                                    {breadcrumbItems.map((item, index) => {
                                        if (item.url) {
                                            return (
                                                <li key={index} className="breadcrumb-item">
                                                    <Link href={item.url} className="text-capitalize">{item.title}</Link>
                                                </li>
                                            );
                                        }

                                        return (
                                            <li className="breadcrumb-item text-capitalize active" aria-current="page">{ item.title }</li>
                                        );
                                    })}

                                    <li className="breadcrumb-item text-capitalize active" aria-current="page">{ title }</li>
                                </ol>
                            </nav>
                        ) : (<div className="mb-3"></div>)}

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
                                    Copyright © 2020 {config.title} - Provided by Juzaweb
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
