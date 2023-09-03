import React from "react";

const Row = React.lazy(() => import('./elements/row'));
const Col = React.lazy(() => import('./elements/col'));
const Card = React.lazy(() => import('./elements/card'));
const StatsCard = React.lazy(() => import('./elements/stats-card'));
const Line = React.lazy(() => import('./charts/line'));
const DataTable = React.lazy(() => import('./elements/data-table'));
const ButtonGroup = React.lazy(() => import('./elements/buttons/button-group'));
const Link = React.lazy(() => import('@inertiajs/react'));
const Editor = React.lazy(() => import('./elements/inputs/editor'));

const Elements = (config: any, index: number) => {
    switch (config.element) {
        case 'row':
            return <Row {...config} key={index}/>;
        case 'col':
            return <Col {...config} key={index}/>;
        case 'card':
            return <Card {...config} key={index}/>;
        case 'stats-card':
            return <StatsCard {...config} key={index}/>;
        case 'line-chart':
            return <Line {...config} key={index}/>;
        case 'data-table':
            return <DataTable {...config} key={index}/>;
        case 'button-group':
            return <ButtonGroup {...config} key={index}/>;
        case 'link':
            return <Link {...config} key={index}>
                <ElementBuilderChildren children={config.children}/>
                {config.text || ''}
            </Link>;
        case 'editor':
            return <Editor {...config} />
    }

    return null;
}

export default function ElementBuilderChildren({children}: { children?: Array<any> }) {
    return children?.map((child: any, index: number) => {
        return Elements(child, index);
    });
}