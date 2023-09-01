import ElementBuilderChildren from "./ElementBuilderChildren";

export default function ElementBuilder({ builder }: any) {
    return <ElementBuilderChildren children={builder.children} />;
}
