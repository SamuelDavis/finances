/* @refresh reload */
import { render } from "solid-js/web";
import "./index.css";
import App from "./Pages/App";
import Layout from "./Pages/Layout";
import { Route, Router } from "@solidjs/router";
import NotFound from "./Pages/NotFound";

render(
  () => (
    <Router root={Layout}>
      <Route path="/" component={App} />
      <Route path="*404" component={NotFound} />
    </Router>
  ),
  document.body,
);
