import StatsCard from "./elements/stats-card";
import Card from "./elements/card";
import Row from "./elements/row";
import Col from "./elements/col";
import Line from "./charts/line";

const Elements = (config: any, index: number) => {
    switch (config.element) {
        case 'row': return <Row {...config} key={index} />;
        case 'col': return <Col {...config} key={index} />;
        case 'card': return <Card {...config} key={index} />;
        case 'stats-card': return <StatsCard {...config} key={index} />;
        case 'chart-line': return <Line {...config} key={index} />;
    }

    return null;
}

export default function ElementBuilderChildren({ children }: { children?: Array<any> }) {
    return children?.map((child: any, index: number) => {
        return Elements(child, index);
    });
}