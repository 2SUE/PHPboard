import { useEffect, useState } from "react";
import { useParams, useNavigate, Link, useLocation, Routes, Route } from "react-router-dom"
import { NoticeDetail } from "./detail/NoticeDetail";
import { NoticeList } from "./list/NoticeList";
import { NoticeWrite } from './write/NoticeWrite';
import './notice.scss';

export const Notice:React.FC = ():JSX.Element => {
    const { uuid } = useParams();   
    const uid =  window.sessionStorage.getItem('uuid');
    const navigate = useNavigate();
    const { search } = useLocation();
    const nowPage = search === ''? 0: parseInt(search.split('=')[1])-1;
    const [page, setPage] = useState<number>(nowPage);

    useEffect(() => {  
        if (uuid !== uid) navigate('/');
        setPage(nowPage);        
    }, [search, uuid, uid, navigate, setPage, nowPage]);
    
    return(
        <div className="notice">
            <div className="notice-header">
                <Link to={'/'+uuid}>
                    <span>게시판</span>
                </Link>
            </div>
            <div className="notice-area">
                <Routes>
                    <Route path='' element={<NoticeList page={nowPage} setPage={setPage}/>}/>
                    <Route path=':id' element={<NoticeDetail/>}/>
                    <Route path='write' element={<NoticeWrite/>}/>
                    <Route path=':id/mod' element={<NoticeWrite/>}/>
                </Routes>
            </div>
        </div>
    );
}