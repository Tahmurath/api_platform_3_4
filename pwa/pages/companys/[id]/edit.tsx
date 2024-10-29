import {
  GetStaticPaths,
  GetStaticProps,
  NextComponentType,
  NextPageContext,
} from "next";
import DefaultErrorPage from "next/error";
import Head from "next/head";
import { useRouter } from "next/router";
import { dehydrate, QueryClient, useQuery } from "react-query";

import { Form } from "../../../components/company/Form";
import { PagedCollection } from "../../../types/collection";
import { Company } from "../../../types/Company";
import { fetch, FetchResponse, getItemPaths } from "../../../utils/dataAccess";

const getCompany = async (id: string | string[] | undefined) =>
  id ? await fetch<Company>(`/companies/${id}`) : Promise.resolve(undefined);

const Page: NextComponentType<NextPageContext> = () => {
  const router = useRouter();
  const { id } = router.query;

  const { data: { data: company } = {} } = useQuery<
    FetchResponse<Company> | undefined
  >(["company", id], () => getCompany(id));

  if (!company) {
    return <DefaultErrorPage statusCode={404} />;
  }

  return (
    <div>
      <div>
        <Head>
          <title>{company && `Edit Company ${company["@id"]}`}</title>
        </Head>
      </div>
      <Form company={company} />
    </div>
  );
};

export const getStaticProps: GetStaticProps = async ({
  params: { id } = {},
}) => {
  if (!id) throw new Error("id not in query param");
  const queryClient = new QueryClient();
  await queryClient.prefetchQuery(["company", id], () => getCompany(id));

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
    },
    revalidate: 1,
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const response = await fetch<PagedCollection<Company>>("/companies");
  const paths = await getItemPaths(
    response,
    "companies",
    "/companys/[id]/edit"
  );

  return {
    paths,
    fallback: true,
  };
};

export default Page;
