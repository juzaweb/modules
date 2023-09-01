import ElementBuilder from "../ElementBuilder";

export default function Col({ config }: { config: any }) {
    return <div className={config.class} id={config.id}>
        <ElementBuilder builder={config} />
    </div>;
}