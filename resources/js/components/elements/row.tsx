import ElementBuilder from "../ElementBuilder";

export default function Row({ config }: { config: any }) {
    return <div className={config.class} id={config.id}>
        <ElementBuilder builder={config} />
    </div>;
}