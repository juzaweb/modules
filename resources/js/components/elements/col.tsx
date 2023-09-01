import ElementBuilderChildren from "../ElementBuilderChildren";

export interface ColProps {
    className: string;
    id?: string;
    children?: Array<any>
}

export default function Col({ className, id, children }: ColProps) {
    return <div className={className} id={id}>
        <ElementBuilderChildren children={children} />
    </div>;
}