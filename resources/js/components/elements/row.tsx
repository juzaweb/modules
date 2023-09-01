import ElementBuilderChildren from "../ElementBuilderChildren";

export interface RowProps {
    className: string;
    id?: string;
    children?: Array<any>
}

export default function Row({ className, id, children }: RowProps) {
    return <div className={className} id={id}>
        <ElementBuilderChildren children={children} />
    </div>;
}