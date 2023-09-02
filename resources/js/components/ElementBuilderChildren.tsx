import StatsCard from "./elements/stats-card";
import Card from "./elements/card";
import Row from "./elements/row";
import Col from "./elements/col";
import Line from "./charts/line";
import DataTable from "./elements/data-table";
import ButtonGroup from "./elements/buttons/button-group";
import {Link} from "@inertiajs/react";

const Elements = (config: any, index: number) => {
    switch (config.element) {
        case 'row': return <Row {...config} key={index} />;
        case 'col': return <Col {...config} key={index} />;
        case 'card': return <Card {...config} key={index} />;
        case 'stats-card': return <StatsCard {...config} key={index} />;
        case 'line-chart': return <Line {...config} key={index} />;
        case 'data-table': return <DataTable {...config} key={index} />;
        case 'button-group': return <ButtonGroup {...config} key={index} />;
        case 'link': return <Link {...config} key={index}>{config.text || ''}</Link>;
    }

    return null;
}

export default function ElementBuilderChildren({ children }: { children?: Array<any> }) {
    return children?.map((child: any, index: number) => {
        return Elements(child, index);
    });
}