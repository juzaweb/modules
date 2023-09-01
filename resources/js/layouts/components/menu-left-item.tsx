export default function MenuLeftItem({item}: {item: any}) {
    return (
        <li className={`juzaweb__menuLeft__item juzaweb__menuLeft__item-${item.slug}` + (item.active ? ' juzaweb__menuLeft__submenu--toggled' : '')}>
            <a className={`juzaweb__menuLeft__item__link`+ (item.active ? ' juzaweb__menuLeft__item--active' : '')}
               href={item.url}>
                {item.icon ? <i className={`juzaweb__menuLeft__item__icon ${item.icon}`}></i> : ''}
                <span className="juzaweb__menuLeft__item__title">{item.title}</span>
            </a>
        </li>
    );
}