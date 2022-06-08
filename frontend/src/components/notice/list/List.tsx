import { useState, useEffect } from "react";
import { Item } from "../Item/Item";
import { Link } from 'react-router-dom';
import axios from 'axios'
import './list.scss';
import { IBoardTypes } from "../../Interfaces";

interface IPageProp {
    page: number;
    setPage: any;
}

export const List:React.FC<IPageProp> = ({page, setPage}:IPageProp):JSX.Element => {
    const [list, setList] = useState<IBoardTypes[]>([]);
    const [total, setTotal] = useState(0);
    const [limit, setLimit] = useState(1);

    useEffect(() => {
        axios.post('/api/selNoticeList.php', JSON.stringify({page}))
       .then(res => {                         
           let resNotice: IBoardTypes[] = [];
            for (let i = 0; i < res.data.length-1 ; i++) {
                resNotice[i] = {
                    id: res.data[i][0],
                    title: res.data[i][1],
                    reg_date: res.data[i][2],
                    state: res.data[i][3],
                    view_count: res.data[i][4]
                } 
            }  
            setList(resNotice);

            const totalCount:number = res.data[res.data.length-1];
            setTotal(totalCount);
            setLimit(Math.ceil(totalCount / 10));
        }); 
    }, [page]);    

    return (    
        <div className="noticeList">
            <div className="noticeList-head">
                <div>공지글 {total}개</div>
                <Link to={'write'} className="btn">글쓰기</Link>
            </div>

            <div className="noticeList-items">
                <table>
                    <thead>
                        <tr>
                            <td className="table-id">글번호</td>
                            <td>제목</td>
                            <td className="none">날짜</td>
                            <td className="none">조회수</td>
                        </tr>
                    </thead>

                    <tbody>
                        {list.map(item => {
                            return <Item 
                                        key={item.id}
                                        id={item.id}
                                        title={item.title}
                                        content={item.content}
                                        reg_date={item.reg_date}
                                        view_count={item.view_count}
                                        state={item.state}
                                    />
                        })}
                    </tbody>

                    <tfoot>
                        <tr className="noticeList-items-page">
                            <td colSpan={4}>
                                {
                                    limit > 5
                                    ?   
                                    <>
                                        {
                                            page < 1
                                            ? <a>◂</a>
                                            : <Link to={'?page='+(page)} onClick={() => setPage(page)}>◂</Link>
                                        }

                                        {
                                            ([...Array(limit)].map((p, i) => {
                                                return (
                                                    <Link key={i} to={'?page='+(i+1)} onClick={() => setPage(i)} className={(page === i? 'noticeList-items-page-active': '')}>{i+1}</Link>
                                                )
                                            }))
                                        }

                                        {
                                            page > limit-2
                                            ? <a>▸</a>
                                            : <Link to={'?page='+(page+2)} onClick={() => setPage(page+1)}>▸</Link>
                                        }
                                    </>
                                    :
                                    ([...Array(limit)].map((p, i) => {
                                        return (
                                            <Link key={i} to={'?page='+(i+1)} onClick={() => setPage(i)} className={(page === i? 'noticeList-items-page-active': '')}>{i+1}</Link>
                                        )
                                    }))
                                }
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    );
}