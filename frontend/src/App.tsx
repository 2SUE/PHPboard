import { Routes, Route, Navigate } from 'react-router-dom';
import { Access } from './components/admin/access/Access';
import { Notice } from './components/admin/notice/Notice';
import './app.scss';

export const App = () => {
  return (
    <Routes>
      <Route path='/'>
        <Route path='' element={<Access />}/>
        <Route path=':uuid/*' element={<Notice/>}/>
      </Route>

      {/* 404 에러 라우팅 */}
      <Route path='/*' element={<Navigate replace to='/'/>}/>
    </Routes>
  );
}