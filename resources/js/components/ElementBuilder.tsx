import StatsCard from "./elements/stats-card";
import Card from "./elements/card";
import Row from "./elements/row";
import Col from "./elements/col";

const Elements = (config: any) => {
    switch (config.element) {
        case 'row': return <Row config={config} />;
        case 'col': return <Col config={config} />;
        case 'card': return <Card config={config} />;
        case 'stats-card': return <StatsCard config={config} />;
    }

    return null;
}

export default function ElementBuilder({ builder }: any) {
    return builder.children.map((child: any) => {
        return Elements(child);
    });
}