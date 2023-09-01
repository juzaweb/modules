import {usePage} from "@inertiajs/react";
import MenuLeftItem from "./menu-left-item";

export default function MenuLeft() {
    const {leftMenuItems} = usePage<{ leftMenuItems: any[] }>().props;

    return <ul className="juzaweb__menuLeft__navigation">
        {leftMenuItems.map((item, index) => {
            if (item?.children) {
                return (
                    <li key={index} className={`juzaweb__menuLeft__item juzaweb__menuLeft__submenu juzaweb__menuLeft__item-${item.slug}` + (item.active ? ' juzaweb__menuLeft__submenu--toggled' : '')}>
                        <span className="juzaweb__menuLeft__item__link">
                            <i className={`juzaweb__menuLeft__item__icon ${item.icon}`}></i>
                            <span className="juzaweb__menuLeft__item__title">{item.title}</span>
                        </span>

                        <ul className="juzaweb__menuLeft__navigation" style={item.active ? {display: 'block'} : {}}>
                            {item.children.map((child: any, childIndex: number) => <MenuLeftItem key={childIndex} item={child}/>)}
                        </ul>
                    </li>
                )
            } else {
                return <MenuLeftItem key={index} item={item}/>;
            }
        })}
    </ul>;
}