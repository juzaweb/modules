import {DatatableColumn, DatatableProps} from "../../types/datatable";
import BulkActions from "./datatable/bulk-actions";
import Search from "./datatable/search";
import {useEffect, useState} from "react";
import axios from "axios";

const dataUrlBuider = (props: DatatableProps) => {
    let url = props.dataUrl;
    if (props.perPage) {
        url += `?limit=${props.perPage}`;
    }

    if (props.sortName) {
        url += `&sortName=${props.sortName}`;
    }

    if (props.sortOder) {
        url += `&sortOder=${props.sortOder}`;
    }

    return url;
}

export default function DataTable(props: DatatableProps) {
    const {columns, dataUrl, uniqueId, actions} = props;

    const [data, setData] = useState<{ rows: Array<any>, total: number }>({rows: [], total: 0});

    useEffect(() => {
        axios.get(dataUrlBuider(props)).then((res) => {
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
                                    //onChange={() => setCheckedAll(this.checkedAll)}
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
                        <tr key={index}>
                            {actions && actions.length > 0 && (
                            <td>
                                <input
                                    type="checkbox"
                                    name={'ids[]'}
                                    className={'jw-checkbox'} value={row.id}
                                    //checked={checkedAll}
                                />
                            </td>
                            )}

                            {columns.map((column: DatatableColumn, index: number) => (
                                <td
                                    key={index}
                                >
                                    {row[column.key]}
                                </td>
                            ))}
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </>
    );
}
