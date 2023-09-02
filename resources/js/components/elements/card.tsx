import ElementBuilderChildren from "../ElementBuilderChildren";

export interface CardProps {
    title?: string;
    className: string;
    children?: Array<any>
}

export default function Card({title, className, children}: CardProps) {
    return (
        <div className={className}>
            {title ? (
                <div className="card-header">
                    <h3 className="card-title">{title}</h3>
                </div>
            ) : ''}

            <div className="card-body">
                <ElementBuilderChildren children={children} />
            </div>
        </div>
    );
}