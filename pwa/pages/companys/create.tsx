import { NextComponentType, NextPageContext } from "next";
import Head from "next/head";

import { Form } from "../../components/company/Form";

const Page: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>Create Company</title>
      </Head>
    </div>
    <Form />
  </div>
);

export default Page;
