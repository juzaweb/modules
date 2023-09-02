import {DatatableColumn, DatatableProps} from "../../types/datatable";
import BulkActions from "./datatable/bulk-actions";
import Search from "./datatable/search";
import {useEffect, useState} from "react";
import axios from "axios";
import Row from "./datatable/row";

const dataUrlBuider = (url: string, perPage: number, sortName?: string, sortOder?: string) => {
    if (perPage) {
        url += `?limit=${perPage}`;
    }

    if (sortName) {
        url += `&sortName=${sortName}`;
    }

    if (sortOder) {
        url += `&sortOder=${sortOder}`;
    }

    return url;
}

export default function DataTable(props: DatatableProps) {
    const {columns, dataUrl, uniqueId, actions} = props;

    const [data, setData] = useState<{ rows: Array<any>, total: number }>({rows: [], total: 0});
    const [sortName, setSortName] = useState(props.sortName);
    const [sortOder, setSortOder] = useState(props.sortOder);
    const [perPage, setPerPage] = useState(props.perPage || 20);
    const [checkedAll, setCheckedAll] = useState(false);

    useEffect(() => {
        axios.get(dataUrlBuider(props.dataUrl, perPage, sortName, sortOder)).then((res) => {
            setData(res.data);
        });
    }, [dataUrl]);

    return (
        <>
            <div className="row">
                <BulkActions {...props}/>

                <Search {...props}/>
            </div>

            <div className="table-responsive">
                <table
                    className="table jw-table"
                    id={uniqueId}
                >
                    <thead>
                        <tr>
                            {actions && actions.length > 0 && (
                                <th data-width="3%" data-checkbox="true">
                                    <input
                                        type="checkbox"
                                        className={'jw-checkbox'}
                                        value={'all'}
                                        onChange={(e) => setCheckedAll(e.target.checked)}
                                    />
                                </th>
                            )}

                            {columns.map((column: DatatableColumn, index: number) => (
                                <th
                                    key={index}
                                    data-width={column.width || 'auto'}
                                    data-align={column.align || 'left'}
                                    data-field={column.key}
                                    data-sortable={column.sortable || true}
                                >{column.label}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {data && data.rows.map((row: any, index: number) => (
                            <Row table={props} row={row} key={index} checked={checkedAll}/>
                        ))}
                    </tbody>
                </table>
            </div>
        </>
    );
}
