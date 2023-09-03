import { MenuItem } from "./menu"
import { Page, PageProps, ErrorBag, Errors } from "@inertiajs/inertia";

export interface Config {
    title: string
    description: string
    logo: string
}

export interface BaseAdminPageProps extends Page<PageProps> {
    [key: string]: unknown;
    props: {
        errors: Errors & ErrorBag,
        flash: {
            success?: string,
            error?: string,
        },
        currentTheme: string,
        user: {
            id: number,
            name: string,
            email: string,
            avatar: string,
        },
        langs: any,
        currentLang: string,
        trans: any,
        adminUrl: string,
        adminPrefix: string,
        totalNotifications: number,
        leftMenuItems: MenuItem[],
        currentUrl: string,
        currentPath: string,
        config: {
            title: string,
            description: string,
        },
        breadcrumbItems: Array<{
            title: string,
            url?: string
        }>
    }
}

export interface BasePageProps extends Page<PageProps> {
    [key: string]: unknown;
    props: {
        errors: Errors & ErrorBag,
        config: Config,
        menu_items: MenuItem[],
        title: string,
        description: string,
        canonical?: string,
    }
}
