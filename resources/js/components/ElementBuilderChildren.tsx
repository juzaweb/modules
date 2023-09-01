import StatsCard from "./elements/stats-card";
import Card from "./elements/card";
import Row from "./elements/row";
import Col from "./elements/col";

const Elements = (config: any) => {
    switch (config.element) {
        case 'row': return <Row {...config} />;
        case 'col': return <Col {...config} />;
        case 'card': return <Card {...config} />;
        case 'stats-card': return <StatsCard {...config} />;
    }

    return null;
}

export default function ElementBuilderChildren({ children }: { children?: Array<any> }) {
    return children?.map((child: any) => {
        return Elements(child);
    });
}